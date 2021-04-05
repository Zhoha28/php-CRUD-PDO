<?php
$username = 'root';
$password = '*t^(vL=GqFU(';

// creating an instance of PDO
## Benefits of using PDO is that it is easy to transition into another database.
## mysqli works with only mysql db

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud', $username, $password);

// so that if there is an error you can display it
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$search = $_GET['search']?? '';
if($search){
  $statement = $pdo->prepare('SELECT * FROm products WHERE title LIKE :title  ORDER BY create_date DESC');

  $statement->bindValue(':title',"%$search%");
}

else{

$statement = $pdo->prepare('SELECT * FROm products ORDER BY create_date DESC');

}
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" cont ent="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <title>Product CRUD</title>

  <style>
    body {
      padding: 50px;
    }
  </style>
</head>

<body>
  <h1>Products</h1>

  <p>
    <a href="create.php" class="btn btn-success">Create Product</a>
  </p>



<!-- search bar -->

<form action="" method="get">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Search Product" aria-label="Search Product" aria-describedby="basic-addon2"
  name="search" value="<?php echo $search ?>">
  <div class="input-group-append">
    <button class="btn btn-primary" type="button">Search</button>
  </div>
</div>
</form>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Image</th>
        <th scope="col">Title</th>
        <th scope="col">Price</th>
        <th scope="col">Create Date</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>

      <?php
      foreach ($products as $i => $product) : ?>
        <tr>
          <th scope="row"><?php echo $i + 1 ?></th>

          <td>
            <img src="<?php echo $product['image'] ?>" alt="<?php echo $product['title'] ?>" height="50" width="50" style="border-radius: 50%;">
          </td>
          <td>
            <?php echo $product['title'] ?>
          </td>
          <td> <?php echo $product['price'] ?></td>
          <td> <?php echo $product['create_date'] ?></td>
          <td>
            <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn  btn-sm btn-outline-primary">Edit</a>

            <form style="display:inline-block;" method="POST" action="delete.php">
              <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php
      endforeach

      ?>

    </tbody>
  </table>

</body>

</html>