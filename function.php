<?php
// ตั้งค่า Timezone
date_default_timezone_set('Asia/Bangkok');
?>

<style>
    .disabled-link {
        pointer-events: none; /* ปิดการคลิก */
        color: gray; /* เปลี่ยนสีลิงก์ให้ดูเหมือน disabled */
        text-decoration: none; /* ลบเส้นใต้ */
        cursor: not-allowed; /* เปลี่ยนลูกศรเมาส์ */
    }
</style>

<?php
// หาอายุจากวันเกิด
function getAgeDetail($birthDate) {
    if (empty($birthDate)) return "-";

    try {
        $birth = new DateTime($birthDate);
        $today = new DateTime();
        $interval = $birth->diff($today);

        return $interval->y . " ปี " . $interval->m . " เดือน " . $interval->d . " วัน";
    } catch (Exception $e) {
        return "-";
    }
}

// ฟังก์ชันสำหรับส่งการแจ้งเตือนไปที่ Messaging API
function sendLineMessagingApi($sMessage, $groupId) {

    $channelAccessToken = "UYofBPCFGm/l2z8AmHo2OI3b3KEo9BS2nEj59BMvGJjX/x37ejt+H+F7HILdVT1MUCBSkIoumcNCAItB43dnkUBd+AXxuIk6csuDezL/DFp1qAY4SzmK3twrz9Gt/1F44fZB2s/HoDIQ+r9VUXEZ0QdB04t89/1O/w1cDnyilFU=";

    // ตั้งค่าข้อมูลในการส่งข้อความ
    $postData = [
        'to' => $groupId,
        'messages' => [
            [
                'type' => 'text',
                'text' => $sMessage
            ]
        ]
    ];

    // แปลงข้อมูลเป็น JSON
    $postDataJson = json_encode($postData, JSON_UNESCAPED_UNICODE); // ป้องกันการเข้ารหัสภาษาไทย

    // เริ่มต้น cURL สำหรับส่งข้อมูลไปยัง LINE Messaging API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $channelAccessToken
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);

    // ดำเนินการส่งข้อความและตรวจสอบการตอบกลับ
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
}

// ตรวจสอบสถานะและ id ของสมาชิก เพื่ออนุญาติเข้าใช้งาน
function checkAndRedirect($status, $userID, $allowedStatus, $allowedUserIDs) {
    // Allow specific userIDs regardless of status
    if (in_array($userID, $allowedUserIDs)) {
        return;
    }
    if (!in_array($status, $allowedStatus)) {
        header("Location: auth-logout.php");
        exit();
    }
}

// ฟังก์ชั่นเพื่อบันทึกการเข้าถึงหน้าของผู้ใช้
function logUserPageAccess($userId, $pageName, $remark, $idRow, $conn) {
    if ($userId == '3') { // เปลี่ยนเป็น 0 ถ้าไม่ต้องการซ่อน 
        // ตรวจสอบว่า session ได้รับการตั้งค่าสำหรับ user_id 3 แล้วหรือยัง
        if (!isset($_SESSION['page_access_logged'])) {
            // ทำการ insert ข้อมูลสำหรับ user_id 3
            $sql = "INSERT INTO tbl_page_access (user_id, page_name, remark, id_row, access_time) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId, $pageName, $remark, $idRow]);

            // ตั้งค่า session เพื่อบันทึกว่าได้ทำการ insert แล้ว
            $_SESSION['page_access_logged'] = true;
        }
    } else {
        // ทำการ insert ข้อมูลสำหรับ user_id อื่นๆ
        $sql = "INSERT INTO tbl_page_access (user_id, page_name, remark, id_row, access_time) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId, $pageName, $remark, $idRow]);
    }
}

function getStartDate($conn) {
    $sql = "SELECT start_date FROM tbl_start_date ORDER BY id DESC LIMIT 1";
    $stmt = $conn->query($sql);
    return $stmt->fetchColumn();
}

// ฟังก์ชันดึงจำนวนแถวทั้งหมดของผู้ใช้ในช่วงวันที่ 
function getRowCountUser($conn, $userId, $action = null, $dateStart = null, $dateEnd = null) {
    try {
        $sql = "SELECT COUNT(*) 
                FROM tbl_page_access 
                WHERE user_id = :userId";

        if ($dateStart && $dateEnd) {
            $sql .= " AND DATE(access_time) BETWEEN :dateStart AND :dateEnd";
        } else {
            $sql .= " AND DATE(access_time) = CURDATE()";
        }

        if ($action) {
            $sql .= " AND page_name LIKE :action";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        if ($dateStart && $dateEnd) {
            $stmt->bindParam(':dateStart', $dateStart);
            $stmt->bindParam(':dateEnd', $dateEnd);
        }

        if ($action) {
            $likeAction = '%' . $action . '%';
            $stmt->bindParam(':action', $likeAction, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error in getRowCountUser: " . $e->getMessage());
        return 0;
    }
}

// ฟังก์ชันแสดงข้อมูลในตาราง (auth-page-access-logs-list-2)
function renderTable($conn, $userId, $userName, $dateStart = null, $dateEnd = null) {
    $sql = "SELECT * FROM tbl_page_access AS d1 
            INNER JOIN tbl_user AS d2 
            ON (d1.user_id = d2.user_id) 
            WHERE d1.user_id = :userId";

    if ($dateStart && $dateEnd) {
        $sql .= " AND DATE(access_time) BETWEEN :dateStart AND :dateEnd";
        $rowCountAll = getRowCountUser($conn, $userId, null, $dateStart, $dateEnd);
        $rowCountInsert = getRowCountUser($conn, $userId, 'insert', $dateStart, $dateEnd);
        $rowCountUpdate = getRowCountUser($conn, $userId, 'update', $dateStart, $dateEnd);
        $rowCountDelete = getRowCountUser($conn, $userId, 'delete', $dateStart, $dateEnd);
    } else {
        $sql .= " AND DATE(access_time) = CURDATE()";
        $rowCountAll = getRowCountUser($conn, $userId);
        $rowCountInsert = getRowCountUser($conn, $userId, 'insert');
        $rowCountUpdate = getRowCountUser($conn, $userId, 'update');
        $rowCountDelete = getRowCountUser($conn, $userId, 'delete');
    }

    $sql .= " ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    if ($dateStart && $dateEnd) {
        $stmt->bindParam(':dateStart', $dateStart);
        $stmt->bindParam(':dateEnd', $dateEnd);
    }

    $stmt->execute();

    echo "<div class='col-md-4'>
            <div class='card table-container mb-3'>
                <div class='card-header d-flex'>
                    <div class='mt-1'>{$userName} &nbsp;ID{$userId}</div>
                    <div class='ms-auto'>
                        {$rowCountAll} . <span style='color:green'>{$rowCountInsert}</span> . <span style='color:blue'>{$rowCountUpdate}</span> . <span style='color:red'>{$rowCountDelete}</span>
                    </div>
                </div>
                <div class='card-body table-responsive'>
                    <table class='table table-bordered table-hover' style='width:100%'>
                        <thead>
                            <tr>
                                <th>Page Name</th>
                                <th class='text-center'>Row</th>
                                <th class='text-center'>Access Time</th>
                            </tr>
                        </thead>
                        <tbody>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $linkClass = 'link-secondary';
        if (strpos($row['page_name'], 'insert') !== false) {
            $linkClass = 'link-success disabled-link';
        } elseif (strpos($row['page_name'], 'isdelete') !== false) {
            $linkClass = 'link-danger disabled-link';
        } elseif (strpos($row['page_name'], 'update') !== false) {
            $linkClass = 'link-primary disabled-link';
        } elseif (strpos($row['page_name'], 'dashboard') !== false || strpos($row['page_name'], 'index') !== false) {
            $linkClass = 'link-dark';
        }

        echo "<tr>
                <td><a class='text-decoration-none {$linkClass}' href='{$row['page_name']}.php'>{$row['page_name']}</a></td>
                <td class='text-center'>{$row['id_row']}</td>
                <td class='text-center'>{$row['access_time']}</td>
              </tr>";
    }

    echo "      </tbody>
                </table>
            </div>
          </div>
        </div>";
}

// ฟังก์ชันแสดงข้อมูลในตาราง (auth-login-list-2)
function renderTable1($conn, $userId, $userName) {
    $sql = "SELECT * FROM tbl_login_log AS d1 
            INNER JOIN tbl_user AS d2 
            ON (d1.user_id = d2.user_id) 
            WHERE DATE(login_log_date) = CURDATE()
            AND d1.user_id = :userId 
            ORDER BY login_log_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $m = count($data);

    echo "<div class='col-md-3'>
            <div class='card table-container mb-3'>
                <div class='card-header'>{$userName} &nbsp;ID{$userId}</div>
                <div class='card-body table-responsive'>
                    <table class='table table-bordered table-hover' style='width:100%'>
                        <thead>
                            <tr>
                                <th class='text-center'>No.</th>
                                <th class='text-center'>Date</th>
                                <th class='text-center'>Time</th>
                            </tr>
                        </thead>
                        <tbody>";

    foreach ($data as $index => $row) {
        echo '<tr>
                <td class="text-center">' . ($m - $index) . '</td>
                <td class="text-center">' . date('d-m-Y', strtotime($row['login_log_date'])) . '</td>
                <td class="text-center">' . date('H:i:s', strtotime($row['login_log_date'])) . '</td>
            </tr>';
    }

    echo "              </tbody>
                    </table>
                </div>
            </div>
        </div>";
}

function getRowCountSince($conn, $startDate, $action = null) {
    $baseSql = "SELECT COUNT(*) 
                FROM tbl_page_access 
                WHERE access_time >= :startDate";

    // ตรวจสอบว่ามีการระบุ action หรือไม่
    if ($action) {
        $baseSql .= " AND page_name LIKE :action";
    } else {
        $baseSql .= " AND (page_name LIKE '%insert%' OR page_name LIKE '%update%' OR page_name LIKE '%delete%')";
    }

    $stmt = $conn->prepare($baseSql);

    // กำหนดค่าให้กับพารามิเตอร์ SQL
    $params = ['startDate' => $startDate];
    if ($action) {
        $params['action'] = '%' . $action . '%';
    }

    $stmt->execute($params);
    return $stmt->fetchColumn();
}

function updateStartDate($conn) {
    date_default_timezone_set('Asia/Bangkok');
    $sql = "INSERT INTO tbl_start_date (start_date) VALUES (:startDate)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'startDate' => date('Y-m-d H:i:s')
    ]);
}

function DateDiff($strDate1,$strDate2)
{
    return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
}

function TimeDiff($strTime1,$strTime2)
{
    return (strtotime($strTime2) - strtotime($strTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
}

function TimeDiff1($strTime1, $strTime2)
{
    return (strtotime($strTime2) - strtotime($strTime1)) / 60; // 1 Hour = 60 minutes
}

function DateTimeDiff($strDateTime1,$strDateTime2)
{
    return (strtotime($strDateTime2) - strtotime($strDateTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
}

function getUserName($conn, $userID) {
    $sql = "SELECT firstname FROM tbl_user WHERE user_id = :userID";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':userID', $userID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['firstname'] : 'N/A';
}

function getRawmatName($conn, $rawmatID) {
    $sql = "SELECT rawmat_name FROM tbl_rawmat WHERE rawmat_id = :rawmatID";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':rawmatID', $rawmatID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['rawmat_name'] : 'N/A';
}

function getRawmatName1($conn, $rawmatID) {
    $sql = "SELECT rawmat_name1 FROM tbl_rawmat WHERE rawmat_id = :rawmatID";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':rawmatID', $rawmatID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['rawmat_name1'] : 'N/A';
}

function getCustomerName($conn, $customerID) {
    $sql = "SELECT customer_name FROM tbl_customer WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':customer_id', $customerID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['customer_name'] : 'N/A';
}

function getSupplierName($conn, $supplierID) {
    $sql = "SELECT supplier_name FROM tbl_supplier WHERE supplier_id = :supplier_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':supplier_id', $supplierID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['supplier_name'] : 'N/A';
}

function getProductName($conn, $productID) {
    $sql = "SELECT product_name FROM tbl_product WHERE product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':product_id', $productID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['product_name'] : 'N/A';
}

function getUnitName($conn, $unitID) {
    $sql = "SELECT unit_name FROM tbl_unit WHERE unit_id = :unit_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':unit_id', $unitID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['unit_name'] : 'N/A';
}

function getSizeValue($conn, $sizeID) {
    $sql = "SELECT size_value FROM tbl_size WHERE size_id = :size_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':size_id', $sizeID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['size_value'] : 'N/A';
}

function getPackageName($conn, $packageID) {
    $sql = "SELECT package_name FROM tbl_package WHERE package_id = :packageID";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':packageID', $packageID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['package_name'] : 'N/A';
}

function getLocationName($conn, $locationID) {
    $sql = "SELECT location_name FROM tbl_location WHERE location_id = :locationID";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':locationID', $locationID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['location_name'] : 'N/A';
}

function updateRawMaterials($idpro, $multiplier, $conn) {
    $sql_formula = "SELECT d1.formula_amount, d2.rawmat_id FROM tbl_formula AS d1
    INNER JOIN tbl_rawmat AS d2 
    ON (d1.rawmat_id = d2.rawmat_id) WHERE d1.product_id = :idpro";
    $stmt_formula = $conn->prepare($sql_formula);
    $stmt_formula->bindParam(':idpro', $idpro);
    $stmt_formula->execute();

    while ($row = $stmt_formula->fetch(PDO::FETCH_ASSOC)) {
        $formulaAmount = $row["formula_amount"] * $multiplier;
        $rawmatId = $row["rawmat_id"];
        $sql_update = "UPDATE tbl_rawmat SET rawmat_amount1 = rawmat_amount1 - :formulaAmount WHERE rawmat_id = :rawmatId";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':formulaAmount', $formulaAmount, PDO::PARAM_STR);
        $stmt_update->bindParam(':rawmatId', $rawmatId, PDO::PARAM_INT);
        $stmt_update->execute();
    }
}

function formatPassTime($passTime)
{
    if ($passTime >= 1440) { // 1 day = 1440 minutes
        $days = floor($passTime / 1440);
        return $days . " " . ($days > 1 ? "days" : "day");
    } elseif ($passTime >= 60) { // 1 hr = 60 minutes
        $hours = floor($passTime / 60);
        return $hours . " " . ($hours > 1 ? "hrs" : "hr");
    } else {
        return number_format($passTime) . " min";
    }
}

function getMarkerColor($text)
{
    switch ($text) {
        case 'New order placed!':
            return '#ffc107'; // warning 
        case 'New production placed!':
            return '#ac3973'; // custom
        case 'New transportation placed!':
            return '#0d6efd'; // primary
        case 'New purchase placed!':
            return '#212529'; // dark
        case 'New inventory placed!':
            return '#6c757d'; // secondary
        case 'New stock placed!':
            return '#0dcaf0'; // info
        case 'New return placed!':
            return '#dc3545'; // danger
        case 'New bill placed!':
            return '#ff9900'; // custom
        case 'New user':
            return '#198754'; // success
        case 'New example placed!':
            return '#999900'; // custom
        case 'New quotation placed!':
            return '#cc33ff'; // custom
        case 'New receipt/invoice placed!':
            return '#572a48ff'; // custom
        case 'New billing note placed!':
            return '#814f15ff'; // custom
        case 'New payment placed!':
            return '#008000'; // green
        case 'New payment1 placed!':
            return '#FF0000'; // red
        case 'New cheque placed!':
            return '#a32864ff'; // custom
        default:
            return '';
    }
}

// ฟังก์ชั่นสำหรับแสดงการแจ้งเตือน
function showHolidayAlert($title, $description, $imageUrl) {
    ?>
    <script>
        Swal.fire({
            confirmButtonText: "Enter",
            showCancelButton: false,
            showCloseButton: false,
            title: "<strong><span style='color:#ac3973;'><?= $title ?></span></strong>",
            html: '<?= $description ?>',
            imageUrl: "<?= $imageUrl ?>",
            imageWidth: 800,
            imageHeight: 200,
            imageAlt: "Holiday Image",
            timer: 20000
        });
    </script>
    <?php
}

// ฟังก์ชั่นเพื่อแปลงจากปี ค.ศ. เป็น พ.ศ.
function convertToBuddhistYear($year) {
    return $year + 543;
}

// ฟังก์ชั่นเพื่อแปลงชื่อวันเป็นภาษาไทย
function getThaiDayName($englishDayName) {
    $days = [
        'Monday' => 'จันทร์',
        'Tuesday' => 'อังคาร',
        'Wednesday' => 'พุธ',
        'Thursday' => 'พฤหัสบดี',
        'Friday' => 'ศุกร์',
        'Saturday' => 'เสาร์',
        'Sunday' => 'อาทิตย์'
    ];
    return $days[$englishDayName] ?? $englishDayName;
}

// ฟังก์ชั่นเพื่อแปลงชื่อเดือนเป็นภาษาไทย
function getThaiMonthName($englishMonthName) {
    $months = [
        'January' => 'มกราคม',
        'February' => 'กุมภาพันธ์',
        'March' => 'มีนาคม',
        'April' => 'เมษายน',
        'May' => 'พฤษภาคม',
        'June' => 'มิถุนายน',
        'July' => 'กรกฎาคม',
        'August' => 'สิงหาคม',
        'September' => 'กันยายน',
        'October' => 'ตุลาคม',
        'November' => 'พฤศจิกายน',
        'December' => 'ธันวาคม'
    ];
    return $months[$englishMonthName] ?? $englishMonthName;
}

function Convert($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".","");
    $pt = strpos($amount_number , ".");
    $number = $fraction = "";
    if ($pt === false) 
        $number = $amount_number;
    else
    {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }
    
    $ret = "";
    $baht = ReadNumber ($number);
    if ($baht != "")
        $ret .= $baht . "บาท";
    
    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else 
        $ret .= "ถ้วน";
    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }
    
    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
            ((($divider == 10) && ($d == 1)) ? "" :
            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}

// ฟังก์ชันสำหรับจัดรูปแบบเลขบัตรประชาชนให้มีขีด
function formatCitizenID($id) {
    // ลบช่องว่างหรือเครื่องหมายที่ไม่ใช่ตัวเลขออกก่อน
    $id = preg_replace('/\D/', '', trim($id));

    // ตรวจสอบว่ามีความยาว 13 หลักหรือไม่
    if (strlen($id) == 13) {
        return substr($id, 0, 1) . '-' .
               substr($id, 1, 4) . '-' .
               substr($id, 5, 5) . '-' .
               substr($id, 10, 2) . '-' .
               substr($id, 12, 1);
    } else {
        return $id; // ถ้าไม่ครบ 13 หลัก ให้แสดงตามเดิม
    }
}

// ฟังก์ชันสำหรับจัดรูปแบบเบอร์โทรศัพท์ให้มีขีด
function formatPhone($number) {
    // ลบช่องว่างหรือเครื่องหมายที่ไม่ใช่ตัวเลขออกก่อน
    $number = preg_replace('/\D/', '', trim($number));

    // ตรวจสอบว่ามีความยาว 10 หลักหรือไม่
    if (strlen($number) == 10) {
        return substr($number, 0, 3) . '-' . 
                substr($number, 3, 3) . '-' . 
                substr($number, 6, 4);
    } else $number; // ถ้าไม่ครบ 10 หลัก ให้แสดงตามเดิม
}

?>