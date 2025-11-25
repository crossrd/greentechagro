<?php if ($status != '0') : ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">GreenTech</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw mt-1"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="auth-register-edit.php?id=<?=$userID?>"><i class="fa-solid fa-user me-2"></i>แก้ไขข้อมูลส่วนบุคคล</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="auth-logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php else : ?> 
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <?php 
            // Logo GreenTech
            if ($status == '3') { 
                echo '<a class="navbar-brand ps-3" href="sb-admin-pro/dist/dashboard-1.html">GreenTech</a>';
            } elseif ($status == '2' && $userID == '2' || $userID == '7') { 
                echo '<a class="navbar-brand ps-3" href="dashboard-3.php">GreenTech</a>';
            } elseif ($status == '2' && $userID == '9') { 
                echo '<a class="navbar-brand ps-3" target="_blank" href="https://parthadas1974.wixsite.com/greentechagro/copy-of-about-us">GreenTech</a>';
            } elseif ($status == '1') {
                echo '<a class="navbar-brand ps-3" href="dashboard-1.php">GreenTech</a>';
            } else {
                echo '<a class="navbar-brand ps-3" href="index.php">GreenTech</a>';
            }
        ?>
        
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto pe-3">
            <?php 
                // Home
                if ($status == '3') { 
                    echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="dashboard-5.php">Home</a></li>';
                } elseif ($status == '2' && $userID == '9') { 
                    echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="dashboard-4.php">Home</a></li>';
                } elseif ($status == '2' && $userID == '7' || $userID == '2') { 
                    echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="dashboard-3.php">Home</a></li>';
                } elseif ($status == '1') { 
                    echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="dashboard-1.php">Home</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>';
                }
            ?>
            <li class="nav-item"><a class="nav-link" href="message-management-add-message.php">Contact</a></li>
            <?php
                if(isset($_SESSION['messageAdded'])){
                    echo '<li class="nav-item"><a class="nav-link" href="message-management-list-1.php"><span class="text-warning"><i class="fa-regular fa-bell mt-1"></i></span></a></li>';
                }
            ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw mt-1"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="auth-register-edit.php?id=<?=$userID?>"><i class="fa-solid fa-user me-2"></i>แก้ไขข้อมูลส่วนบุคคล</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <?php if ($status == '3') : ?>
                        <li><a class="dropdown-item" href="wallet-management-list.php"><i class="fa-solid fa-wallet me-2"></i>My Wallet</a></li>
                        <li><a class="dropdown-item" href="event-management-list.php"><i class="fa-solid fa-calendar-days me-2"></i>Calendar</a></li>
                        <li><a class="dropdown-item" href="star.php"><i class="fa-solid fa-star me-2"></i>Star</a></li>
                        <li><hr class="dropdown-divider" /></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="auth-logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>

                        <!-- Dashboard -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts6" aria-expanded="false" aria-controls="collapseLayouts6">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts6" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php $i = 1; ?>
                                <a class="nav-link" href="dashboard-1.php">Dashboard <?=$i++?></a> 
                                <a class="nav-link" href="dashboard-2.php">Dashboard <?=$i++?></a> 
                                <?php 
                                    if ($status == '2' || $status == '3') {
                                        echo '<a class="nav-link" href="dashboard-3.php">Dashboard ' . $i++ . '</a>';
                                        echo '<a class="nav-link" href="dashboard-4.php">Dashboard ' . $i++ . '</a>';
                                    } 
                                    
                                    if ($status == '3') {
                                        echo '<a class="nav-link" href="dashboard-5.php">Dashboard ' . $i++ . '</a>';
                                    }
                                ?> 
                            </nav>
                        </div>

                        <div class="sb-sidenav-menu-heading">Interface</div>

                        <!-- GTA ChatBot -->
                        <?php
                            if ($status == '1' || $status == '2 ' || $status == '3') {
                                echo '<a class="nav-link" href="n8n-web-chatbot.php">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-robot"></i></div>
                                        GTA ChatBot<span class="text-info ms-2">Beta</span>
                                    </a>';
                            }
                        ?>

                        <!-- Production -->
                        <?php
                            $link = ($status == '2' || $status == '3' || $userID == '10' || $userID == '15') 
                                    ? "sales-management-list-4.php" 
                                    : "sales-management-list-6.php";

                            echo '<a class="nav-link" href="' . $link . '">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-industry"></i></div>
                                    Production';

                            if (isset($_SESSION['productOrderAdded']) || isset($_SESSION['transportOrderAdded'])) {
                                echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                            }

                            echo '</a>';
                        ?>

                        <?php if ($status == '2' || $status == '3' || $userID == '10' || $userID == '11' || $userID == '15') : ?>
                        <!-- Finance -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts20" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                            Finance
                            <?php
                                if(isset($_SESSION['receiptAdded']) || isset($_SESSION['receipt1Added']) || isset($_SESSION['paymentAdded']) || isset($_SESSION['payment1Added']) || isset($_SESSION['chequeAdded'])) {
                                    echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                }
                            ?>
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts20" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="receipt-management-list.php">
                                    เอกสารการเงิน
                                    <?php
                                        if(isset($_SESSION['receiptAdded'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="receipt1-management-list.php">
                                    ใบวางบิล
                                    <?php
                                        if(isset($_SESSION['receipt1Added'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="payment-management-list.php">
                                    รายการรับ
                                    <?php
                                        if(isset($_SESSION['paymentAdded'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="payment1-management-list.php">
                                    รายการจ่าย
                                    <?php
                                        if(isset($_SESSION['payment1Added'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="cheque-management-list.php">
                                    แลกเช็ค
                                    <?php
                                        if(isset($_SESSION['chequeAdded'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                            </nav>
                        </div>
                        <?php endif; ?>

                        <!-- ทำรายการ -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            ทำรายการ
                            <?php
                                if(isset($_SESSION['salesOrderAdded']) || isset($_SESSION['purchaseOrderAdded']) || isset($_SESSION['inventoryAdded']) || isset($_SESSION['stockAdded']) || isset($_SESSION['billAdded']) || isset($_SESSION['returnOrderAdded']) || isset($_SESSION['exampleAdded']) || isset($_SESSION['quotationAdded'])) {
                                    echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                }
                            ?>
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="sales-management-list.php">
                                    ขายสินค้า
                                    <?php
                                        if(isset($_SESSION['salesOrderAdded'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="sales-transport-list-5.php">
                                    ถังเปล่า
                                </a>
                                <?php
                                    $menus = [
                                        [
                                            'condition' => ($status == '2' || $status == '3' || $userID == '10' || $userID == '11'), // สุรินทร์ || หนึ่ง
                                            'link' => 'purchase-management-list.php',
                                            'text' => 'สั่งซื้อวัตถุดิบ',
                                            'session_key' => 'purchaseOrderAdded'
                                        ],
                                        [
                                            'condition' => ($status == '2' || $status == '3' || $userID == '10' || $userID == '11' || $userID == '14' || $userID == '15'), // สุรินทร์ || หนึ่ง || ศรายุทธ || ดาว
                                            'link' => 'inventory-management-list.php',
                                            'text' => 'รับวัตถุดิบเข้าคลัง',
                                            'session_key' => 'inventoryAdded'
                                        ]
                                    ];

                                    foreach ($menus as $menu) {
                                        if ($menu['condition']) {
                                            echo '<a class="nav-link" href="' . $menu['link'] . '">' . $menu['text'];

                                            if (isset($_SESSION[$menu['session_key']])) {
                                                echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                            }

                                            echo '</a>';
                                        }
                                    }
                                ?>
                                <a class="nav-link" href="stock-management-list.php">
                                    ตัดสต๊อควัตถุดิบ
                                    <?php
                                        if(isset($_SESSION['stockAdded'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="return-management-list.php">
                                    สินค้าคืน
                                    <?php
                                        if(isset($_SESSION['returnOrderAdded'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="quotation-management-list.php">
                                    ใบเสนอราคา
                                    <?php
                                        if(isset($_SESSION['quotationAdded'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="example-management-list.php">
                                    เบิกตัวอย่างสินค้า
                                    <?php
                                        if(isset($_SESSION['exampleAdded'])){
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <a class="nav-link" href="bill-management-list.php">
                                    ค่าใช้จ่าย
                                    <?php
                                        if (isset($_SESSION['billAdded'])) {
                                            echo '<span class="badge bg-primary-soft text-primary ms-auto mt-1">Updated</span>';
                                        }
                                    ?>
                                </a>
                                <!-- <a class="nav-link" href="activity-management-list.php">กิจกรรมพนักงาน</a> -->
                            </nav>
                        </div>

                        <!-- ข้อมูลพื้นฐาน -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts2">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            ข้อมูลพื้นฐาน
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="product-management-list.php">ข้อมูลสินค้า</a>
                                <a class="nav-link" href="rawmat-management-list.php">ข้อมูลวัตถุดิบ</a>
                                <?php
                                    if ($status == '2' || $status == '3' || $userID == '10') { // สุรินทร์ 
                                        echo '<a class="nav-link" href="customer-management-list.php">ข้อมูลลูกค้า</a>';
                                        echo '<a class="nav-link" href="supplier-management-list.php">ข้อมูลผู้ขาย</a>';
                                    } 
                                ?>
                                <a class="nav-link" href="employee-management-list.php">ข้อมูลพนักงาน</a>
                                <?php
                                    if ($status == '2' || $status == '3' || $userID == '14') { // ศรายุทธ
                                        echo '<a class="nav-link" href="formula-management-list.php">ข้อมูลสูตรสินค้า</a>';
                                    }

                                    if ($status == '2' || $status == '3') {
                                        echo '<a class="nav-link" href="rawmat5-management-list.php">ข้อมูลสูตรเคมี</a>';
                                        echo '<a class="nav-link" href="price-management-list.php">ข้อมูลราคาสินค้า</a>';
                                    }
                                ?>
                                <a class="nav-link" href="stirler-management-list.php">ข้อมูลถังปั่น</a>
                                <a class="nav-link" href="equip-management-list.php">ข้อมูลอุปกรณ์</a>
                                <a class="nav-link" href="document-management-list.php">ข้อมูลเอกสารสำคัญ</a>
                                <a class="nav-link" href="registration-management-list.php">ข้อมูลทะเบียนสินค้า</a>
                            </nav>
                        </div>
                        
                        <!-- จัดการประเภท -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts3">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            จัดการประเภท
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="type-management-list.php">จัดการประเภท</a>
                                <a class="nav-link" href="type1-management-list.php">จัดการค่าใช้จ่าย</a>
                                <a class="nav-link" href="package-management-list.php">จัดการหน่วย</a>
                                <a class="nav-link" href="unit-management-list.php">จัดการหน่วยนับ</a>
                                <a class="nav-link" href="size-management-list.php">จัดการขนาด</a>
                                <a class="nav-link" href="location-management-list.php">จัดการสถานที่</a>
                            </nav>
                        </div>

                        <!-- แจ้งการชำระเงิน -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts7" aria-expanded="false" aria-controls="collapseLayouts7">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            แจ้งการชำระเงิน
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts7" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="customer-payment.php">ส่งหลักฐานการชำระเงิน</a>
                                <a class="nav-link" href="customer-report-order-list.php">เช็คสถานะการสั่งซื้อสินค้า</a>
                            </nav>
                        </div>

                        <!-- งานทดลอง -->
                        <?php 
                            if ($status == '2' || $status == '3') { 
                                echo '<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts4" aria-expanded="false" aria-controls="collapseLayouts4">
                                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                    งานทดลอง
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseLayouts4" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="lab-management-list.php">รายการทดลอง</a>
                                        <a class="nav-link" href="formula1-management-list.php">รายการสูตรทดลอง</a>
                                    </nav>
                                </div>';   
                            } 
                        ?>

                        <!-- ผู้ดูแลระบบ -->
                         <?php
                            if ($status == '3' || $status == '2') {
                                echo '<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts5" aria-expanded="false" aria-controls="collapseLayouts5">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-lock"></i></div>
                                        ผู้ดูแลระบบ
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="collapseLayouts5" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                        <nav class="sb-sidenav-menu-nested nav">';
                                            if ($status == '3') {  
                                                echo '<a class="nav-link" href="auth-register-list.php">ข้อมูลผู้ลงทะเบียน</a>';
                                                echo '<a class="nav-link" href="auth-login-list-1.php">ประวัติการเข้าใช้งาน</a>';
                                                echo '<a class="nav-link" href="auth-page-access-logs-list-1.php">ประวัติการเข้าหน้าเว็บ</a>';
                                                echo '<a class="nav-link" href="sales-transport-list-3-1.php">ยอดขายสินค้า</a>';
                                                echo '<a class="nav-link" href="message-management-list.php">ข้อความทั้งหมด</a>';
                                                echo '<a class="nav-link" href="rawmat3-management-list.php">สต๊อคโรงงานไทรน้อย</a>';
                                                echo '<a class="nav-link" href="customer-management-list-top-sales.php">จัดอันดับยอดขาย</a>';
                                                echo '<a class="nav-link" href="purchase1-management-list.php">PO (ต่างประเทศ)</a>';
                                                echo '<a class="nav-link" href="product-management-list-1.php">ข้อมูลสินค้า (AI)</a>';
                                                echo '<a class="nav-link" href="rawmat-management-list-3.php">ข้อมูลวัตถุดิบ (AI)</a>';
                                            } elseif ($status == '2') {
                                                echo '<a class="nav-link" href="formula-management-list.php">ข้อมูลสูตรสินค้า</a>';
                                                echo '<a class="nav-link" href="price-management-list.php">ข้อมูลราคาสินค้า</a>';
                                                if ($userID == '7' || $userID == '2') { // สุรวดี || เอกมล
                                                    echo '<a class="nav-link" href="sales-transport-list-3-1.php">ยอดขายสินค้า</a>';
                                                    echo '<a class="nav-link" href="wallet-management-list-2.php">สิบลด</a>';
                                                } elseif ($userID == '9') { // สุน
                                                    echo '<a class="nav-link" href="sales-transport-list-3-3.php">ยอดขายสินค้า</a>';
                                                    echo '<a class="nav-link" href="report-management-list.php">กระแสเงินสดรับ-จ่าย<span class="text-warning ms-2">New</span></a>';
                                                    echo '<a class="nav-link" href="report1-management-list.php">เงินเดือนพนักงาน<span class="text-warning ms-2">New</span></a>';
                                                    echo '<a class="nav-link" href="purchase1-management-list.php">PO (ต่างประเทศ)</a>';
                                                }
                                                echo '<a class="nav-link" href="customer-management-list-top-sales.php">จัดอันดับยอดขาย</a>';
                                            }
                                echo '</nav>
                                    </div>';
                            }
                        ?>

                        <!-- คำนวนราคา -->
                        <?php
                            if($status == '2' || $status == '3'){ 
                                echo '<a class="nav-link" href="price-calculate.php">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-calculator"></i></div>
                                        คำนวนราคา
                                    </a>';
                            }
                        ?>
                     
                        <!-- จัดการข้อความ -->
                        <a class="nav-link" href="message-management-list-1.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-inbox"></i></div>
                            จัดการข้อความ
                            <?php
                                if(isset($_SESSION['messageAdded'])){
                                    echo '<span class="badge bg-warning-soft text-warning ms-auto mt-1">New Message</span>';
                                }
                            ?>
                        </a>

                        <!-- Calendar -->
                        <!-- <a class="nav-link" href="event-management-list.php">
                            <div class="sb-nav-link-icon"><i class="fa-regular fa-calendar"></i></div>
                            Calendar
                        </a> -->

                        <!-- Addons -->
                        <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="charts.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                            <a class="nav-link" href="map-user-add-location.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-location-dot"></i></div>
                                Track Location
                            </a>
                            <?php
                                if ($status == 3) {
                                    echo '<a class="nav-link" href="https://parthadas1974.wixsite.com/greentechagro/copy-of-about-us" target="_blank">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-earth-americas"></i></div>
                                        Landing Page
                                    </a>';
                                }
                            ?>
                            <a class="nav-link" href="n8n-web-maintenance.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-wrench"></i></div>
                                แจ้งซ่อมทางนี้<span class="text-warning ms-2">New</span>
                            </a>
                        </div>
                    </div>

                    <?php if ($status == '3') : ?>
                        <a href="https://lin.ee/WdzJKiG" class="text-center">
                            <img src="https://scdn.line-apps.com/n/line_add_friends/btn/th.png" alt="เพิ่มเพื่อน" height="36" border="0">
                        </a>
                    <?php endif; ?>

                    <!-- Logged in as: -->
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php
                            if (isset($_SESSION["firstname"])) {
                                echo $_SESSION["firstname"] . " &nbsp;" . $_SESSION["lastname"] . "&nbsp;";
                            }

                            if ($status == '3') {
                                echo "(DBA)";
                            } elseif ($status == '2') {
                                echo "(SA)";
                            } elseif ($status == '1') {
                                echo "(A)";
                            } else {
                                echo "";
                            }
                        ?>
                    </div>

            </nav>
        </div>     
<?php endif; ?>
         