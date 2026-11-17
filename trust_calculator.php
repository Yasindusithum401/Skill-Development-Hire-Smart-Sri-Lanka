<?php
// Recalculates and caches ETSR metrics (M1 - M4) for an employer
function recalculateEmployerTrustScore(PDO $pdo, int $recruiterId): array {
    // 1. Fetch total applications across all jobs posted by employer
    $stmtApps = $pdo->prepare(
        "SELECT a.id, a.applied_at, a.current_status 
         FROM applications a 
         JOIN jobs j ON j.id = a.job_id 
         WHERE j.recruiter_id = ?"
    );
    $stmtApps->execute([$recruiterId]);
    $applications = $stmtApps->fetchAll(PDO::FETCH_ASSOC);

    // A brand-new application still waiting for its first reply must not lower the score.
    // Only applications that were answered, or that are older than the grace period, are evaluated.
    $graceDays = 5;
    $eligibleIds = [];
    foreach ($applications as $app) {
        $ageDays = (time() - strtotime($app['applied_at'])) / 86400;
        if ($app['current_status'] !== 'Applied' || $ageDays > $graceDays) {
            $eligibleIds[(int)$app['id']] = true;
        }
    }
    $totalApps = count($eligibleIds);

    if ($totalApps === 0) {
        $result = [
            'm1' => 0.0, 'm2' => 0.0, 'm3' => 0.0, 'm4' => 0.0,
            'overall' => 0.0, 'badge' => 'New Employer'
        ];
        saveTrustScore($pdo, $recruiterId, $result);
        return $result;
    }

    // M1: Average Response Time Score (Target <= 5 Working Days)
    $stmtM1 = $pdo->prepare(
        "SELECT a.applied_at, MIN(h.changed_at) AS first_response
         FROM applications a
         JOIN jobs j ON j.id = a.job_id
         JOIN application_status_history h ON h.application_id = a.id
         WHERE j.recruiter_id = ? AND h.status != 'Applied'
         GROUP BY a.id"
    );
    $stmtM1->execute([$recruiterId]);
    $m1Rows = $stmtM1->fetchAll(PDO::FETCH_ASSOC);
    
    $m1ScoreSum = 0;
    $m1Count = count($m1Rows);
    foreach ($m1Rows as $row) {
        $days = (strtotime($row['first_response']) - strtotime($row['applied_at'])) / (60 * 60 * 24);
        if ($days <= 3) $m1ScoreSum += 100;
        elseif ($days <= 5) $m1ScoreSum += 80;
        elseif ($days <= 10) $m1ScoreSum += 50;
        else $m1ScoreSum += 20;
    }
    $m1 = $m1Count > 0 ? round($m1ScoreSum / $m1Count, 2) : 0.0;

    // M2: Application Acknowledgement Rate (% updated beyond 'Applied')
    $acknowledgedCount = 0;
    foreach ($applications as $app) {
        if (isset($eligibleIds[(int)$app['id']]) && $app['current_status'] !== 'Applied') {
            $acknowledgedCount++;
        }
    }
    $m2 = round(($acknowledgedCount / $totalApps) * 100, 2);

    // M3: Rejection Transparency Score
    $stmtM3 = $pdo->prepare(
        "SELECT h.rejection_reason, h.rejection_comment
         FROM application_status_history h
         JOIN applications a ON a.id = h.application_id
         JOIN jobs j ON j.id = a.job_id
         WHERE j.recruiter_id = ? AND h.status = 'Rejected'"
    );
    $stmtM3->execute([$recruiterId]);
    $rejections = $stmtM3->fetchAll(PDO::FETCH_ASSOC);
    $totalRejections = count($rejections);

    if ($totalRejections > 0) {
        $m3ScoreSum = 0;
        foreach ($rejections as $rej) {
            $score = 0;
            if (!empty($rej['rejection_reason'])) $score += 50;
            if (!empty($rej['rejection_comment']) && strlen(trim($rej['rejection_comment'])) >= 30) $score += 50;
            $m3ScoreSum += $score;
        }
        $m3 = round($m3ScoreSum / $totalRejections, 2);
    } else {
        $m3 = 100.0; // Perfect score default if no rejections made yet
    }

    // M4: Process Timeline Updates (% completed applications with > 1 status transition)
    $stmtM4 = $pdo->prepare(
        "SELECT a.id, COUNT(h.id) AS transition_count
         FROM applications a
         JOIN jobs j ON j.id = a.job_id
         LEFT JOIN application_status_history h ON h.application_id = a.id
         WHERE j.recruiter_id = ?
         GROUP BY a.id"
    );
    $stmtM4->execute([$recruiterId]);
    $m4Rows = $stmtM4->fetchAll(PDO::FETCH_ASSOC);
    $updatedCount = 0;
    foreach ($m4Rows as $row) {
        if (isset($eligibleIds[(int)$row['id']]) && $row['transition_count'] >= 2) $updatedCount++;
    }
    $m4 = round(($updatedCount / $totalApps) * 100, 2);

    // M5: Candidate rating (only used once at least one job seeker has rated this employer)
    $stmtRev = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt FROM employer_reviews WHERE recruiter_id = ?");
    $stmtRev->execute([$recruiterId]);
    $rev = $stmtRev->fetch(PDO::FETCH_ASSOC);
    $reviewCount = (int)($rev['cnt'] ?? 0);
    $m5 = $reviewCount > 0 ? round(((float)$rev['avg_rating'] / 5) * 100, 2) : null;

    // Weighted Overall Trust Score Equation
    if ($m5 !== null) {
        $overall = round(($m1 * 0.30) + ($m2 * 0.20) + ($m3 * 0.15) + ($m4 * 0.15) + ($m5 * 0.20), 2);
    } else {
        $overall = round(($m1 * 0.35) + ($m2 * 0.25) + ($m3 * 0.20) + ($m4 * 0.20), 2);
    }

    // Determine Display Badge Threshold
    $badge = ($totalApps >= 5) ? 'Active' : 'Insufficient Data';

    $result = [
        'm1' => $m1, 'm2' => $m2, 'm3' => $m3, 'm4' => $m4, 'm5' => $m5,
        'overall' => $overall, 'badge' => $badge
    ];

    saveTrustScore($pdo, $recruiterId, $result);
    return $result;
}

function saveTrustScore(PDO $pdo, int $recruiterId, array $data): void {
    $stmt = $pdo->prepare(
        "INSERT INTO employer_trust_scores 
            (recruiter_id, m1_response_time, m2_acknowledgement, m3_rejection_transp, m4_timeline_updates, overall_trust_score, display_badge)
         VALUES (?, ?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            m1_response_time = VALUES(m1_response_time),
            m2_acknowledgement = VALUES(m2_acknowledgement),
            m3_rejection_transp = VALUES(m3_rejection_transp),
            m4_timeline_updates = VALUES(m4_timeline_updates),
            overall_trust_score = VALUES(overall_trust_score),
            display_badge = VALUES(display_badge),
            last_calculated = CURRENT_TIMESTAMP"
    );
    $stmt->execute([
        $recruiterId, $data['m1'], $data['m2'], $data['m3'], $data['m4'], $data['overall'], $data['badge']
    ]);
}