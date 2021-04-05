<?php
$username = 'root';
$password = '*t^(vL=GqFU(';

// creating an instance of PDO
## Benefits of using PDO is that it is easy to transition into another database.
## mysqli works with only mysql db

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud', $username, $password);

// so that if there is an error you can display it
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// echo '<pre>';
// var_dump($_FILES);
// echo '</pre>';

// exit;


$errors =[];
$title ='';
$description='';
$price ='';
if($_SERVER['REQUEST_METHOD'] === 'POST'){

$title = $_POST['title'];
$description = $_POST['description'];

$price = $_POST['price'];

$date = date('Y-m-d H:i:s');



if(!$title){
  $errors[] = "Product title is required";
}

if(!$price){
  $errors[] = "Product price is required";
}

if(!is_dir('images')){
  mkdir('images');
}

if (empty($errors)){

  $image = $_FILES['image'] ?? null;
  $imagePath = '';

  if($image && $image['tmp_name']){
    
    $imagePath = 'images/'.randomizeName(8).'/'.$image['name'];

    mkdir(dirname($imagePath));

    move_uploaded_file($image['tmp_name'],$imagePath);
  }

// prepare statement helps reduce sql injections
$statement = $pdo->prepare("INSERT INTO products(title, description, image, price, create_date)
 VALUES (:title,:description,:image,:price,:date)");

$statement->bindValue(':title',$title);
$statement->bindValue(':description',$description);
$statement->bindValue(':image',$imagePath);
$statement->bindValue(':price',$price);
$statement->bindValue(':date',$date);


$statement->execute();
header('Location:index.php');
}




}

function  randomizeName($n){
  $characters = '0123456789abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = '';
  for($i =0;$i < $n ; $i++){
    $index = rand(0,strlen($characters) - 1);
    $str .= $characters[$index];
  }
  return $str;
}

?>



<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" cont ent="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <title>Create Product</title>

  <style>
    body {
      padding: 50px;
    }
  </style>
</head>

<body>
  <h1>Create a new product</h1>

<?php
if(!empty($errors)):
?>
  <div class="alert alert-danger">
    <?php
    foreach($errors as $error): ?>
    <div><?php echo $error ?></div>

   <?php endforeach; ?>
  </div>

  <?php endif; ?>

  <form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label>Product Name</label>
      <input type="text" class="form-control" name="title" placeholder="Enter product Name" value="<?php echo $title ?>">
    </div>
    <div class="form-group">
      <label>Product Description</label>
      <input type="text" class="form-control" name="description" placeholder="Enter products description"
      value="<?php echo $description ?>"
      >
    </div>

    <div class="form-group">
      <label>Product Image</label>
      <input name="image" type="file">
    </div>

    <div class="form-group">
      <label>Product Price</label>
      <input type="number" name="price" step=".01" class="form-control"
      value="<?php echo $price ?>"
      >
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>


</body>

</html>