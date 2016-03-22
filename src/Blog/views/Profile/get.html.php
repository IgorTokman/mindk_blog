<div class="container">
    <div class="row">
        <div class="col-md-5">
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

        <div class="col-md-7">
            <h2 class="text-center">Your articles</h2>
            <table class="table table-striped table-bordered">

                <tr>
                    <td>№</td>
                    <td>Title</td>
                    <td>Content</td>
                    <td>Date</td>
                    <td>Actions</td>
                </tr>

                <?php foreach($posts as $key => $post){ ?>
                    <tr>
                        <td rowspan="2"><?=$key+1?></td>
                        <td rowspan="2"><a href="/posts/<?php echo $post->id ?>"> <?php echo $post->title ?></a></td>
                        <td rowspan="2"><?=substr(htmlspecialchars_decode($post->content), 0, 200) . " ..."?></td>
                        <td rowspan="2"><?=date('F j, Y', strtotime($post->date))?></td>
                        <td>
                            <a href="<?php echo $getRoute('start_edit', array('id' => $post->id))?>" role="button" class="btn-block btn btn-success">Edit</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo $getRoute('remove_post', array('id' => $post->id))?>" role="button" class="btn-block btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?} ?>
            </table>

        </div>
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




