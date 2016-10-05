<!DOCTYPE html>
<head>
<title> OOP BLOG </title>
</head>
<?php
require 'database.php';

require 'tags.php';

$database = new database;

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

if(@$_POST['delete']){
  $delete_id = $_POST['delete_id'];
  $database->query('DELETE FROM blog_posts WHERE id = :id');
  $database->bind(':id', $delete_id);
  $database->execute();
}

if(@$post['update']){
  $id = $post['id'];
  $title = $post['title'];
  $post = $post['post'];

  $database->query('UPDATE blog_post SET title = :title, post = :post WHERE id = :id');
  $database->bind(':title', $title);
  //$database->bind(':post' $post);
  $database->bind(':id', $id);
  $database->execute();
}

if(@$post['submit']){
  $id = $post['id'];
  $title = $post['title'];
  $post = $post['post'];
  $database->query('INSERT INTO blog_posts (id,title, post, date_posted) VALUES(:id, :title, :post, CURRENT_TIMESTAMP)');
  $database->bind(':id', $id);
  $database->bind(':title', $title);
  $database->bind(':post', $post);
  $database->execute();
  if($database->lastInsertId()){
    echo 'Post added!';
  }
}
$database->query('SELECT * FROM blog_posts');
$rows = $database->resultset();

?>

<head>
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<h1> Add Posts </h1>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>"
<label> POST ID </label><br/>
  <input type="text" name="id" placeholder="Specify Id" required /> <br /><br />
<label>Post Title</label><br />
<input type="text" name="title" placeholder="Add a Title..." required /><br /><br />
<!-- <label>Post Date</label><br />
<input type="date" name="date" required  /><br /><br /> -->
<label>Post Body</label><br />
<textarea name="post" required> </textarea><br /><br />
<input type="submit" name="submit" value="Submit"  />
</form>
<h1>Posts</h1>
<div>
  <?php foreach($rows as $row) : ?>
    <div>
      <h3>
           <?php echo $row['title'];?>
       </h3>
       <p>
           <?php echo $row['post']; ?>
       </p>
       <br />
       <span class="footer">
           <?php echo 'Date Created: ' .$row['date_posted']; ?>
       </span>
   </div>
   <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
       <input type="hidden" name="delete_id" value="<?php echo $row['id'] ?>">
       <input type="submit" name="delete" value="Delete" />
   </form>
<?php endforeach; ?>
</div>
</body>
</html>
