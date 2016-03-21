<div class="container">
    <div class="row">
        <span class="pull-left profile glyphicon glyphicon-user" aria-hidden="true"></span>
        <a href="#callback" type="button" class="fancybox edit btn btn-primary">Edit your profile</a>
        <dl class="user-info col-md-3">
            <dt>User id:</dt>
            <dd><?=$user->id?></dd>
            <dt>User role:</dt>
            <dd><?=$user->role?></dd>
            <dt>User email:</dt>
            <dd><?=$user->email?></dd>
        </dl>
    </div>

    <?php if (!isset($errors)) {
        $errors = array();
    } ?>

    <div class="hidden">
        <form class="form-signin" id="callback" role="form" method="post" action="<?php echo $getRoute('update_profile')?>">
            <h3 class="form-signin-heading">Edit your profile</h3>
            <?php  foreach($errors as $error) {  ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <strong>Error!</strong> <?php echo $error ?>
                </div>
            <?php }  ?>
            <input type="hidden" class="form-control" value="<?=$user->id?>" name="id">
            <input type="email" class="form-control" value="<?=$user->email?>" required autofocus name="email">
            <input type="text" class="form-control" value="<?=$user->role?>" required autofocus name="role">
           <!-- <input type="password" class="form-control" placeholder="Enter your new password"  name="password">-->
            <button class="btn btn-lg btn-primary btn-block" type="submit">Edit</button>
            <?php $generateToken()?>
        </form>
    </div>

</div>






















