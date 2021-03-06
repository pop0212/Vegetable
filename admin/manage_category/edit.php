<?php
  require_once '../db/function.php';

  $id = ''; // bien chung

  if (isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = 'select * from category where id = '.$id;

    $category = executeSingleResult($sql); // lay 1 doi tuong

    if ($category != null){
      $name = $category['name'];
    }else{
      $id = '';
    }
  }

  if(!empty($_POST)){
    $name = '';
    if (isset($_POST['edit_name_category'])){
      $name = $_POST['edit_name_category'];
    }

    $sqldp = "select * from category where name = '$name'";
        $checkName = executeSingleResult($sqldp);

    if (isset($checkName) && $checkName > 0){
      echo "<script>
          $.alert({
              title: 'Thông báo:',
              content: 'Không thay đổi',
          });
      </script>";
      }elseif (!empty($name)){
      date_default_timezone_set("Asia/Ho_Chi_Minh");
      $created_at = $updated_at = date('Y-m-d H:i:s');
      //Luu vao db
      $name = str_replace('\'', '\\\'', $name);
      $id = str_replace('\'', '\\\'', $id);
      
      $sql = "update category set name = '$name', updated_at = '$updated_at' where id = '$id'";
            
      execute($sql);
      header('Location: manage.php?tab=manage_category');
      die();
    }
}
?>

<div class="main">
    <div class="title">
        <a href="">
            <h4>Sửa danh mục</h4>
        </a>
    </div>

    <div class="main-table">
        <div class="header-table">
            <form method="POST" class="form-block">
                <div class="form-group">
                    <label for="edit_name_category" class="form-label">Tên danh mục*</label>
                    <input type="number" name="id" value="<?=$id?>" style="display: none">
                    <input type="text" class="form-control" id ="edit_name_category" required name="edit_name_category" value="<?=$name?>" />
                </div>
                <button class="btn btn-success">Lưu lại</button>
            </form>
        </div>
    </div>
</div>