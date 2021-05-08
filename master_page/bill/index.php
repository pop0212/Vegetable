
<link rel="stylesheet" href="../css/cart.css">

<!--Nội dung trang-->

<!-- Content -->
<div id="content">
    <hr>
    <div class="section-2 section">
        <?php
        if (isset($_SESSION['cart'])){
            $arrayId = [];
            foreach($_SESSION['cart'] as $id_product => $quantity){
                $arrayId[] = $id_product; //truyen tat ca id vao bien kieu mang
            }
            // print_r($arrayId);
            // die();
            $stringId = implode(',', $arrayId); /*chuyen du lieu sang kieu chuoi, 
                                                truyen vao ',' phan cach moi phan tu */

            //phan trang
            if (isset($_GET['page'])){
                $page = $_GET['page'];
            }else{
                $page = 1;
            }
            $rowPerPage = 4; //so hang hien thi
            $perRow = $page * $rowPerPage - $rowPerPage; //chi so bat dau cua trang moi

            $sql2 = "select id from product where id in ($stringId)";

            $totalRow = executeTotalRowsResult($sql2);
            $totalPages = ceil($totalRow/$rowPerPage);
            $listPage = '';
            for ($i = 1; $i <= $totalPages; $i++){
                if ($totalPages == 1){
                    $listPage = '';
                }elseif($page == $i){
                    $listPage .= '<li class="page-item"><a style="border: 1px solid blanchedalmond;
                    color: #fff; background: #80bb01" style="color: rgb(114, 165, 11)" class="page-link"
                    href="index.php?tab=bill&page='.$i.'">'.$i.'</a></li>';
                }else{
                    $listPage .= '<li class="page-item"><a style="color: rgb(114, 165, 11)"
                    class="page-link" href="index.php?tab=bill&page='.$i.'">'.$i.'</a></li>';
                }
            }
            //lay san pham hien thi theo trang
            $sql1 = "select title, id, thumbnail, sale, quantity, price from product where id in ($stringId) limit $perRow, $rowPerPage";
            $productListOnPage = executeResult($sql1); 

            //lay tat ca san pham trong gio hang
            $sql = "select title, id, thumbnail, sale, quantity, price from product where id in ($stringId)";
            $productList = executeResult($sql); 
        ?>
        <div class="cart-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="main-heading">Giỏ hàng</div>
                        <div class="table-cart">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($productListOnPage as $rowOnPage){
                                ?>
                                    <tr>
                                        <td>
                                            <div class="display-flex align-center">
                                                <div class="img-product">
                                                    <img src="<?=$rowOnPage['thumbnail']?>" class="mCS_img_loaded">
                                                </div>
                                                <div class="name-product">
                                                    <?= $rowOnPage['title']?>
                                                </div>
                                                <?php
                                                    if (isset($rowOnPage['sale']) && $rowOnPage['sale'] > 0){
                                                        $sale = $rowOnPage['sale']; //%
                                                        $price = $rowOnPage['price'];
                                                        $priceNew = $price * (100 - $sale)/100;
                                                        echo "<div class='price'>".currency_format($priceNew)."/kg<br><span style='text-decoration: line-through; color: #989898;'>".currency_format($price)."/kg</span></div>";
                                                    }else{
                                                        echo "<div class='price'>".currency_format($rowOnPage['price'])."/kg</div>";
                                                    }
                                                ?>
                                            </div>
                                        </td>
                                        <td class="product-count">
                                            <form action="#" class="count-inlineflex">
                                                <div class="qtyminus">-</div>
                                                <input type="text" name="quantity" value="<?= $_SESSION['cart'][$rowOnPage['id']]?>" class="qty">
                                                <div class="qtyplus">+</div>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="total">
                                                <?php
                                                    $quantity = $_SESSION['cart'][$rowOnPage['id']];
                                                    if (isset($rowOnPage['sale']) && $rowOnPage['sale'] > 0){
                                                        $sale = $rowOnPage['sale']; //%
                                                        $price = $rowOnPage['price'];
                                                        $priceNew = $price * (100 - $sale)/100;
                                                        echo currency_format($priceNew * $quantity);
                                                    }else{
                                                        echo currency_format($rowOnPage['price'] * $quantity);
                                                    }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" title="Xóa">
                                                <i style="font-size: 22px; color: #989898" class="far fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>  
                                </tbody>
                            </table>
                            <div class="navigation" style="position: inherit; display: block;" >
                                <ul class="pagination" style="justify-content:center;">
                                    
                                    <?php
                                        echo $listPage;
                                    ?>
                                    
                                </ul>
                            </div>
                            <div class="coupon-box">
                                <form method="POST" accept-charset="utf-8">
                                    <div class="coupon-input">
                                        <input type="text" name="coupon code" placeholder="Mã giảm giá" name="textCode">
                                        <button type="submit" class="round-black-btn">Áp dụng</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.table-cart -->
                    </div>
                    <!-- /.col-lg-8 -->
                    <div class="col-lg-4">
                        <div class="cart-totals">
                            <h3>Thanh Toán</h3>
                            <?php
                                $totalTemporary = 0;
                                foreach($productList as $row){
                                    $quantity = $_SESSION['cart'][$row['id']];
                                    if (isset($row['sale']) && $row['sale'] > 0){
                                        $sale = $row['sale']; //%
                                        $price = $row['price'];
                                        $priceNew = $price * (100 - $sale)/100;
                                        $totalTemporary += ($priceNew * $quantity);
                                    }else{
                                        $totalTemporary += ($row['price'] * $quantity);
                                    }
                                }
                            ?>
                            <form action="#" method="get" accept-charset="utf-8">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Tạm tính</td>
                                            <td class="subtotal">
                                            <?php
                                                echo currency_format($totalTemporary);
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phí vận chuyển</td>
                                            <td class="free-shipping">
                                            <?php
                                                // $arrayNameCode = [];
                                                // foreach($listCode as $nameCode){
                                                //     $arrayNameCode[] = $nameCode;
                                                // }
                                                // var_dump($listCode);
                                                // $stringCode = implode(',', $arrayNameCode);
                                                // var_dump($stringCode);
                                                // die();
                                                if (isset($_POST['textCode'])){
                                                    $textCode = $_POST['textCode'];
                                                    $slqCode = "select name_code from code";
                                                    $listCode = executeResult($slqCode);

                                                }
                                            ?>
                                            Free Shipping</td>
                                        </tr>
                                        <tr class="total-row">
                                            <td>Tổng tiền</td>
                                            <td class="price-total">
                                                <?php

                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="btn-cart-totals">
                                    <a href="#" class="update round-black-btn" title="">Cập nhật</a>
                                    <a href="#" class="checkout round-black-btn" title="">Tiến Hành Thanh Toán</a>
                                </div>
                                <!-- /.btn-cart-totals -->
                            </form>
                            <!-- /form -->
                        </div>
                        <!-- /.cart-totals -->
                    </div>
                    <!-- /.col-lg-4 -->
                </div>
            </div>
        </div>
    </div>
    <?php
        }else{
            echo "<script>
                $.alert({
                    title: 'Thông báo:',
                    content: 'Không có sản phẩm để hiển thị',
                    buttons: { 
                        ok: function (){
                            location.replace('index.php');
                        }
                    }
                });
            </script>";
        }
    ?>
    <hr>

</div>
