<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    $start_day = new DateTime($_REQUEST[('day_depart')]);
    $timetour = $_REQUEST[('timetour')];
    $format_StartDate = $start_day->format('Y-m-d');

    // Tạo bản sao của $start_day để tính ngày kết thúc tour
    $startDate = clone $start_day;
    $startDate->modify("+{$timetour} days");     // Cộng số ngày
    $end_day = $startDate->format('Y-m-d'); 
    // Truy vấn từ bảng nhân viên
    // $p->getAllHdv("SELECT * FROM employees WHERE `role` = 'hướng dẫn viên'  ORDER BY id ASC");
    $p->getAllHdv("SELECT e.*
                    FROM employees e
                    WHERE e.role = 'hướng dẫn viên'
                    AND NOT EXISTS (
                        SELECT 1
                        FROM `assignment-tour` a
                        INNER JOIN `departure_time` dt ON a.departure_id = dt.id
                        WHERE a.staff_id = e.id
                        AND (
                            ((dt.day_depar <= '$end_day') AND (DATE_ADD(dt.day_depar, INTERVAL $timetour DAY) >= '$format_StartDate'))
                        )
                    )
                    ORDER BY e.id ASC");
?>