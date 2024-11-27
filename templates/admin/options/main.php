<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $name => $title): ?>
            <?php $class = $name == $current_tab ? 'nav-tab-active' : '';  ?>
            <a class="nav-tab <?php echo $class; ?>" href="<?php echo  add_query_arg(array('tab' => $name)); ?>"><?php echo $title; ?></a>
        <?php endforeach;  ?>
    </h2>

    <?php if ($success): ?>
        <div class="notice updated" style="margin-top: 15px;">
            <p><?php echo $message; ?></p>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="notice error" style="margin-top: 15px;">
            <p><?php echo $message; ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <table class="form-table">
            <?php
            if (in_array($current_tab, array_keys($tabs))) {
                include  WPVIP_TPL . 'admin/options/' . $current_tab . '.php';
            }
            ?>
        </table>
        <?php submit_button('ذخیره تنظیمات'); ?>
    </form>
</div>