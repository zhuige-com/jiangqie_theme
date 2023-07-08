<?php if (!defined('ABSPATH')) {
    die;
} // Cannot access directly.
?>

<?php get_header(); ?>

<div class="main-body mt-20">
    <div class="container">
        <div class="row d-flex flex-wrap">
            <!--主内容区-->
            <article class="column xs-12 sm-12 md-8 mb-10-xs mb-0-md">

                <div class="base-list search-list mb-20">
                    <?php jiangqie_breadcrumbs(); ?>
                    <div class="content-wrap" style="text-align: center; font-size: 14px;">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/images/404.jpg' ?>" alt=" " />
                        <p>这里什么也没有~</p>
                    </div>
                </div>

            </article>

            <!--侧边栏-->
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        window.location.href = '/';
    }, 2000)
</script>

<?php get_footer(); ?>