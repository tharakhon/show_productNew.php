<?php
session_start();

if(isset($_SESSION["cart"])){
    array_splice($_SESSION["cart"],0);

}
?>
<script>
    window.alert("นำสินค้าทั้งหมดออกจากตระกร้าเรียบร้อยแล้ว");
    window.location.replace("show_product.php");
</script>