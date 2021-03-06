<?php

 require 'validator.php';
 require 'BlogClass.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

   # Create Validator Obj ..

    $validate = new validator;

    $title      =  $validate->clean($_POST['title']);
    $content    =  $validate->clean($_POST['content']);
 
    $ImageTmp   =  $_FILES['image']['tmp_name'];
    $ImageName  =  $_FILES['image']['name'];
    $ImageSize  =  $_FILES['image']['size'];
    $ImageType  =  $_FILES['image']['type'];
 
    $TypeArray = explode('/', $ImageType);

    $errors = [];

    if (!$validate->validate($title, 1)) {
        $errors['title'] = "Field Required";
    }
    
    if (!$validate->validate($content, 1)) {
        $errors['content'] = "Field Required";
    } elseif (!$validate->validate($content, 3)) {
        $errors['content'] = "content Length Must >= 50ch";
    }

    if (!$validate->validate($ImageName, 1)) {
        $errors['image'] = "Image Field Required";
    } elseif (!$validate->validate($TypeArray[1], 6)) {
        $errors['image'] = "Invalid Extension";
    } else {
        $FinalName = rand(1, 100).time().'.'.$TypeArray[1];
        $disPath = './uploads/'.$FinalName;
    }

    if (move_uploaded_file($ImageTmp, $disPath)) {
        $blog = new  blog();
        $reuslt = $blog->create($title, $content, $FinalName);

        if ($reuslt) {
            echo 'data inserted';
        } else {
            echo 'error try again';
        }
    }

    if (count($errors) > 0) {
        foreach ($errors as $key => $val) {
            echo '* '.$key.' :  '.$val.'<br>';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Blog</h2>
        <form
            action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"
            method="post" enctype="multipart/form-data">



            <div class="form-group">
                <label for="exampleInputEmail1">Title</label>
                <input type="text" name="title" class="form-control" id="exampleInputName" aria-describedby="">
            </div>


            <div class="form-group">
                <label for="exampleInputEmail1">Content</label>
                <input type="text" name="content" class="form-control" id="exampleInputEmail1" aria-describedby="">
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">Image</label>
                <input type="file" name="image">
            </div>

            <button type='submit' class="btn btn-primary btn-lg">Sumbit</button>

            <button class="alert alert-success"><a target="_blank" class="link-primary" href="display.php">Return To
                    Display</a></button>
        </form>
    </div>

</body>

</html>
