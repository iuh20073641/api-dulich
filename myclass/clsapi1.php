<?php
class clsapi
{
    private $conn;

    public function __construct()
    {
        $this->connect($this->conn);
    }

    public function connect(&$conn)
    {
        $conn = mysqli_connect("localhost:3307", "root", "", "venture");
        mysqli_set_charset($conn, 'utf8');
        if (!$conn) {
            echo "Không kết nối được";
            exit();
        }
        return $conn;
    }

    public function execute_query($sql, $values)
    {
        $stmt = mysqli_prepare($this->conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, str_repeat('s', count($values)), ...$values);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        }
        return false;
    }

    public function fetch_all($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return false;
    }
    public function update_user_role($user_id, $role_id, $sql)
    {
        $conn = $this->connect($conn);

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ii', $role_id, $user_id);
            $success = mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
            $this->close_kn($conn);

            if ($success) {
                return ['status' => 'success', 'message' => 'User role updated successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to update user role.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Failed to prepare statement.'];
        }
    }
}
