<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $name => $title): ?>
            <?php $class = ( $name == $currentTab ) ? ' nav-tab-active' : ''; ?>
            <a class='nav-tab<?php echo $class; ?>' href='?page=wps/wps-settings.php&tab=<?php echo $name; ?>'><?php echo $title; ?></a>
        <?php endforeach; ?>
    </h2>
    <form action="" method="POST">
        <?php include WPS_TPL.'admin_settings_'.$currentTab.'.php'; ?>
        <?php submit_button(); ?>
    </form>
</div>
