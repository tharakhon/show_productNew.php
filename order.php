<?php
session_start();
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$address = $_POST["address"];
$moblie = $_POST["moblie"];
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "shop";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Connnect mysql database fail!!" . mysqli_connect_error());
//echo "Connect mysql successfully!";
$sql = "INSERT INTO order_product (fname, lname,address,moblie)";
$sql .= "VALUES ('$fname', '$lname', '$address','$moblie');";
//echo $sql;
if (mysqli_query($conn, $sql)) {
  $last_id = mysqli_insert_id($conn);
  //echo "New record created successfully. Last inserted ID is: " . $last_id;
  // loop in session cart and insert each item to database
  $sql1 = "INSERT INTO order_details (order_id,product_id) VALUES ";
  for ($i = 0; $i < count($_SESSION["cart"]); $i++) {
    $item_id = $_SESSION["cart"][$i]['id'];
    $sql1 .= "('$last_id','$item_id')";
    if ($i < count($_SESSION["cart"]) - 1)
      $sql1 .= ",";
    else
      $sql .= ";";
  }
  //echo $sql1;

  if (mysqli_query($conn, $sql1)) { //echo "บันทึกข้อมูลการสั่งซื้อเรียบร้อยแล้ว";
?>
    <script>
      window.alert("บันทึกข้อมูลการสั่งซื้อเรียบร้อยแล้ว")
    </script>
  <?php
  } else { ?>
    <script>
      window.alert("เกิดข้อผิดพลาดในการสั่งซื้อ")
    </script>
<?php
  }
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
$sql2 = "SELECT * FROM `order_product`,`order_details`,`product`";
$sql2 .= "WHERE `order_product`.id = `order_details`.order_id AND";
$sql2 .= "`order_details`.product_id = `product`.id AND `order_details`.order_id = $last_id";
$result = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result) > 0) {
  $total = 0;
  $check = 0;
  echo "<h1>รายการสินค้า</h1>";
  echo "<table border=1><tr><th>ลำดับ</th><th>product</th>
  <th>description</th><th>price</th></tr>";
  while ($row = mysqli_fetch_assoc($result)) {
    $check++;
    echo "<tr><td>" . $row['order_id'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "<td>" . $row['price'] . "</td>";
    $total += $row['price'];
    if ($check == 1) {
      echo "Firstname: <b>" .$row['fname'].
      "</b> Lastname: <b>".$row['lname'] .
      "</b></br> Address: <b>".$row['address'] .
      "</b></br> Moblie: <b>". $row['moblie']."</b>";
    }
  }
  echo "</table>";
  echo "<h3>Total $total Baht</h3>";
}
echo "<h3><a href='del_all.php'>Home</a></h3>";

mysqli_close($conn);
//$result=mysqli_query($con,$sql);
//$numrow=mysqli_num_rows($result);
?>