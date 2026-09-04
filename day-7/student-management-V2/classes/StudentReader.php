<?php 
class StudentReader{

    function __construct(private PDO $conn){

        $this->conn = $conn;

    }

    public function countStudents($course, $search)
    {
        $count_sql = "SELECT COUNT(*) FROM students";

        $conditions = [];
        $params = [];

        if ($course) {
            $conditions[] = "course = :course";
            $params[':course'] = $course;
        }

        if ($search) {
            $conditions[] = "CONCAT(full_name, email) LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        if (count($conditions) > 0) {
            $count_sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt_count = $this->conn->prepare($count_sql);

        $stmt_count->execute($params);

        return $stmt_count->fetchColumn();
    }

    public function getStudents($course, $search, $limit, $offset) {
        $read_sql = "SELECT * FROM students";

        $conditions = [];
        $params = [];

        if ($course) {
            $conditions[] = "course = :course";
            $params[':course'] = $course;
        }

        if ($search) {
            $conditions[] = "CONCAT(full_name) LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        if (count($conditions) > 0) {
            $read_sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $read_sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt_select = $this->conn->prepare($read_sql);

        foreach ($params as $key => $value) {
            $stmt_select->bindValue($key, $value);
        }

        $stmt_select->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt_select->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt_select->execute();

        return $stmt_select->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
