<?php




$username = 'root';
$password = '*t^(vL=GqFU(';

// creating an instance of PDO
## Benefits of using PDO is that it is easy to transition into another database.
## mysqli works with only mysql db

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud', $username, $password);

// so that if there is an error you can display it
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$id = $_GET['id'] ?? null;

if (!$id) {
  header('Location: index.php');
  exit;
}

$statement = $pdo->prepare('SELECT * FROm products WHERE id= :id');
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);

// echo '<pre>';
// var_dump($product);
// echo '</pre>';



$errors = [];
$title = $product['title'];
$description = $product['description'];
$price = $product['price'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $title = $_POST['title'];
  $description = $_POST['description'];

  $price = $_POST['price'];


  if (!$title) {
    $errors[] = "Product title is required";
  }

  if (!$price) {
    $errors[] = "Product price is required";
  }

  // if images directory is not there then create it
  if (!is_dir('images')) {
    mkdir('images');
  }

  if (empty($errors)) {

    $image = $_FILES['image'] ?? null;
    $imagePath = $product['image'];



    if ($image && $image['tmp_name']) {

      if ($product['image']) {
        unlink($product['image']);
      }

      $imagePath = 'images/' . randomizeName(8) . '/' . $image['name'];

      mkdir(dirname($imagePath));

      move_uploaded_file($image['tmp_name'], $imagePath);
    }


    $statement = $pdo->prepare("
UPDATE products SET title = :title, description = :description, image = :image, price = :price where id = :id
");

    $statement->bindValue(':title', $title);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':image', $imagePath);
    $statement->bindValue(':price', $price);

    $statement->bindValue(':id', $id);

    $statement->execute();
    header('Location:index.php');
  }
}

function  randomizeName($n)
{
  $characters = '0123456789abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = '';
  for ($i = 0; $i < $n; $i++) {
    $index = rand(0, strlen($characters) - 1);
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

  <title>
    <?php echo $title ?>

  </title>

  <style>
    body {
      padding: 50px;
    }
  </style>
</head>

<body>
  <p>
    <a href="index.php" class="btn btn-secondary">Back to Products</a>
  </p>

  <h3>
    Update Product -
    <u><strong style="text-transform: uppercase;"> <?php echo $title ?></strong>
    </u>
  </h3>

  <?php
  if (!empty($errors)) :
  ?>
    <div class="alert alert-danger">
      <?php
      foreach ($errors as $error) : ?>
        <div><?php echo $error ?></div>

      <?php endforeach; ?>
    </div>

  <?php endif; ?>

  <form action="" method="post" enctype="multipart/form-data">


    <?php if ($product['image']) : ?>

      <img src="<?php echo $product['image']; ?>" alt="<?php echo $title ?>" height="150">
    <?php endif ?>
    <div class="form-group">
      <label>Product Name</label>
      <input type="text" class="form-control" name="title" placeholder="Enter product Name" value="<?php echo $title; ?>">
    </div>
    <div class="form-group">
      <label>Product Description</label>
      <input type="text" class="form-control" name="description" placeholder="Enter products description" value="<?php echo $description; ?>">
    </div>

    <div class="form-group">
      <label>Product Image</label>
      <input name="image" type="file">
    </div>

    <div class="form-group">
      <label>Product Price</label>
      <input type="number" name="price" step=".01" class="form-control" value="<?php echo $price; ?>">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>


</body>

</html>