<!-- Standalone Project: 
 Student Result Calculator.
 Process an array of multiple students' results,
 compute rank order,
 and output the results as both an HTML table and a JSON payload. -->

<!-- Project ko:

Multiple students ka data process karna hai
Har student ke 3 subject marks hone chahiye
Total calculate karna hai
Average calculate karna hai
Pass/Fail status calculate karna hai
Average ke basis par rank calculate karni hai
Results ko HTML table mein display karna hai
Same results ko JSON payload ke form mein output karna hai
Total calculate karne ke liye array_sum() jaise aggregate shortcut use nahi karna hai -->

<?php

$students = [
    [
        "name" => "Animesh",
        "marks" => [
            "PHP" => 85,
            "MySQL" => 78,
            "Laravel" => 92
        ]
    ],
    [
        "name" => "Priya",
        "marks" => [
            "PHP" => 90,
            "MySQL" => 88,
            "Laravel" => 95
        ]
    ]
];


/*
|--------------------------------------------------------------------------
| Calculate Student Results
|--------------------------------------------------------------------------
*/

function calculateResults($students)
{
    $results = [];

    foreach ($students as $student) {

        $total = 0;
        $subjectCount = 0;
        $isPass = true;

        foreach ($student["marks"] as $mark) {

            $total = $total + $mark;
            $subjectCount++;

            if ($mark < 40) {
                $isPass = false;
            }
        }

        $average = $total / $subjectCount;

        if ($isPass) {
            $status = "Pass";
        } else {
            $status = "Fail";
        }

        $results[] = [
            "name" => $student["name"],
            "marks" => $student["marks"],
            "total" => $total,
            "average" => $average,
            "status" => $status
        ];
    }

    return $results;
}


/*
|--------------------------------------------------------------------------
| Calculate Results
|--------------------------------------------------------------------------
*/

$results = calculateResults($students);


/*
|--------------------------------------------------------------------------
| Sort Students by Average - Highest First
|--------------------------------------------------------------------------
*/

usort($results, function ($studentA, $studentB) {

    if ($studentA["average"] == $studentB["average"]) {
        return 0;
    }

    return ($studentA["average"] < $studentB["average"]) ? 1 : -1;
});


/*
|--------------------------------------------------------------------------
| Assign Rank
|--------------------------------------------------------------------------
*/

$rank = 1;

foreach ($results as &$student) {

    $student["rank"] = $rank;

    $rank++;
}

unset($student);


/*
|--------------------------------------------------------------------------
| JSON Payload
|--------------------------------------------------------------------------
*/

$jsonPayload = json_encode(
    $results,
    JSON_PRETTY_PRINT
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Result Calculator</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        .pass {
            color: green;
            font-weight: bold;
        }

        .fail {
            color: red;
            font-weight: bold;
        }

        pre {
            background: #f5f5f5;
            padding: 20px;
            overflow-x: auto;
        }

    </style>

</head>

<body>

    <h1>Student Result Calculator</h1>


    <!-- HTML Result Table -->

    <h2>Student Results</h2>

    <table>

        <thead>

            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>PHP</th>
                <th>MySQL</th>
                <th>Laravel</th>
                <th>Total</th>
                <th>Average</th>
                <th>Status</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($results as $student): ?>

                <tr>

                    <td>
                        <?= $student["rank"] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($student["name"]) ?>
                    </td>

                    <td>
                        <?= $student["marks"]["PHP"] ?>
                    </td>

                    <td>
                        <?= $student["marks"]["MySQL"] ?>
                    </td>

                    <td>
                        <?= $student["marks"]["Laravel"] ?>
                    </td>

                    <td>
                        <?= $student["total"] ?>
                    </td>

                    <td>
                        <?= number_format($student["average"], 2) ?>
                    </td>

                    <td class="<?= strtolower($student["status"]) ?>">
                        <?= $student["status"] ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>


    <!-- JSON Output -->

    <h2>JSON Payload</h2>

    <pre><?= htmlspecialchars($jsonPayload) ?></pre>

</body>

</html>

