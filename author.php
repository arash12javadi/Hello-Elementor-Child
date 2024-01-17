<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

global $wpdb;
$authorid = get_the_author_meta('ID');
$query_follow = $wpdb->prepare("SELECT COUNT(*) AS cntfollow FROM wp_ajdwp_like_follow WHERE follow_stat = 'follow' and author_id = " . $authorid);
$result_follow = $wpdb->get_results($query_follow);

if (!empty($result_follow)) {
	$totalfollow = $result_follow[0]->cntfollow;
}

$author_email = get_the_author_meta('user_email');
$user = get_user_by('email', $author_email);
$userId = $user->ID;
$post_count = count_user_posts($userId);

?>

<?php get_header(); ?>

<?php
include dirname(__FILE__) . '/theme_addons/like_follow/likeFollowCounters.php';
include dirname(__FILE__) . '/theme_addons/database_queries/queries.php';

ob_start();
include dirname(__FILE__)."/theme_addons/user_profile/author_page_profile_edit.php";
$author_page_profile_edit = ob_get_contents();
ob_end_clean();

?>

<?php  echo do_shortcode('[user_dashboard_float_button]'); ?>

<div class="content mt-5">

	<div class="container">

		<div class="row d-flex justify-content-center">

			<div class="col-lg-6">

				<div class="author-page">
					<div class="post-author text-left bg-dark bg-gradient text-light border border-5 rounded py-4 px-4">
						<div class="follow_edit d-flex justify-content-between">
							<!-- -------------- FOLLOW BUTTTON -------------- -->
							<div>
								<?php if (is_user_logged_in()) { do_action('AJDWP_like_follow_social');} ?>
							</div>

							<!-- -------------- U S E R - U P D A T E - I N F O - IN A MODAL -------------- -->
							<div>
								<?php  echo $author_page_profile_edit;?>
							</div>

							<!-- -------------- U S E R _ DISPLAY NAME -------------- -->
							<div class="d-flex align-items-center justify-content-center display-6 font-weight-bold">
								<?php echo get_the_author(); ?>
							</div>

							<!-- -------------- U S E R _ AVATAR -------------- -->
							<div class="d-flex align-items-center justify-content-center my-3">
								<?php
									//echo get_avatar($author_email, $size = '200', null, null, array('class' => array('rounded-circle border shadow')));
									
									$custom_avatar_url = get_user_meta($userId, 'custom_avatar_url', true);

									if (!empty($custom_avatar_url)) {
										echo '<img src="' . esc_url($custom_avatar_url) . '" alt="Avatar" class="rounded-circle border shadow" style="width:200px; height:200px;">';
									} else {
										// If custom avatar URL is not set, fall back to the default avatar display
										echo get_avatar($author_email, $size = '200', null, null, array('class' => array('rounded-circle border shadow')));
									}
								?>
							</div>

							<!-- -------------- U S E R _ FIRSR & LAST NAME -------------- -->
							<div class="d-flex align-items-center justify-content-center font-weight-bold h6">
								<?php
								echo get_the_author_meta('first_name') . " " . get_the_author_meta('last_name');
								?>
							</div>

							<!-- -------------- POST_COUNT -------------- -->
							<div class="d-flex align-items-center justify-content-center font-weight-bold h6">
								<?php
								echo "Total Posts: (" . $post_count . ")";
								?>
							</div>

							<!-- -------------- U S E R _ B I O -------------- -->
							<div class="d-flex align-items-center justify-content-start mt-4 pt-4 border-top border-light font-weight-bold h5">
								<?php echo "About me: "; ?>
							</div>

							<div class="text-left justify-content-start ">
								<?php
								the_author_meta('description');
								?>
							</div>

							<div class="text-left justify-content-start mt-4 pt-4 border-top border-light "></div>

							<!-- -------------- E M A I L - A D D R E S S -------------- -->
							<div class="user_profile_email_div ">

								<?php if ($showEmail === 'yes') { ?>

									<div class="font-weight-bold h5">
										<?php echo "Email me: "; ?>
									</div>

									<div class="text-left justify-content-start  ">
										<p>
											<a href="mailto:<?php echo $author_email = get_the_author_meta('user_email'); ?>">
												<?php echo $author_email = get_the_author_meta('user_email'); ?>
											</a>
										</p>
									</div>
								<?php } ?>

							</div>

							<!-- -------------- P H O N E - N U M B E R -------------- -->
							<div class="user_profile_number_div ">
								<?php if ($showPhone === 'yes') { ?>
									<div class="font-weight-bold h5">
										<?php echo "Call me: "; ?>
									</div>
									<div>
										<a href="mailto:<?php echo $author_email = get_the_author_meta('phone_number'); ?>">
											<?php echo $author_email = get_the_author_meta('phone_number'); ?>
										</a>
									</div>
								<?php } ?>
							</div>

							<div class="text-left justify-content-start mt-4 pt-4 border-top border-light "></div>

							<!-- -------------- S O C I A L  -  M E D I A S -------------- -->
							<?php do_action("User_Social_Media"); ?>

							<!-- -------------- U S E R  -  TOTAL FOLLOW & LIKED & PROFILE VIEWED -------------- -->
							<?php if (!is_page()) { ?>
								<div class="like_follow_social border-top mt-4 py-4 d-flex justify-content-center align-items-center">

									<?php

									echo "Total Followers: &nbsp" . $totalfollow;
									?>
									<div class="vr mx-4"></div>
									All likes recieved:
									<?php
									echo $author_posts_total_likes;
									?>
									<div class="vr mx-4"></div>
									Profile view:
									<?php
									if (is_author()) {
										setPostViews(get_the_ID());
									}
									echo getPostViews(get_the_ID());
									?>
								</div>

							<?php } ?>

							<!-- -------------- U S E R - F O L L O W E R S -------------- -->
							<div class="d-block border-top  pt-4 ">
								<p>Followers:</p>
							</div>

							<div class="authors_followers">

								<?php

								if (count($query_fetch) > 0) {
									foreach ($query_fetch as $row) {
										if(!empty(get_userdata($row->followee_id))){
								?>
											<div class="m-1 border border-1 rounded d-inline-block">
												<a href="<?php echo get_author_posts_url($row->followee_id); ?>">
													<div class="g-0 d-flex justify-content-start align-items-center">
														<div class="">
															
															<?php
																$like_follow_custom_avatar_url = get_user_meta($row->followee_id, 'custom_avatar_url', true);

																if (!empty($like_follow_custom_avatar_url)) {
																	echo '<img src="' . esc_url($like_follow_custom_avatar_url) . '" alt="Avatar" class="img-fluid rounded-start" style="width:50px; height:50px;">';
																} else {
																	// If custom avatar URL is not set, fall back to the default avatar display
																	echo get_avatar($row->followee_id, $size = '50px', null, null, array('class' => array('img-fluid rounded-start')));; 
																}							
															?>

														</div>
														<div class="px-2">
															<span class="card-title"><?php echo get_userdata($row->followee_id)->display_name; ?></span>
														</div>
													</div>
												</a>
											</div>
								<?php

										}
									}
								} else {
									echo "Someone follow me, I got no followers :)";
								}
								?>
							</div>
						</div>
					</div>
				</div>

				<!-- ------------------- A U T H O R'S - P O S T S - A R C H I V E ------------------- -->
				<!-- ------------------- A U T H O R'S - P O S T S - A R C H I V E ------------------- -->

				<div class="<?php if (count_user_posts(get_the_author_meta('ID')) > 0) {
								echo 'col-lg-6';
							} else {
								echo 'd-none';
							} ?>">

					<section id="post-section" class="post-section">
						<div class="container">
							<div class="row">

								<?php if (have_posts()) : ?>
									<?php while (have_posts()) : the_post(); ?>

										<article id="post-<?php the_ID(); ?>" <?php post_class('post-items shadow rounded mb-3 '); ?>>
											<div class="row mb-4 mt-4 ">

												<div class="post-date-author col-lg-4 ">

													<div class="d-flex align-items-center justify-content-start">
														<span class="span-postdate ">Post Date: &nbsp;</span>
														<span class="post-date">
															<a href="<?php echo esc_url(get_month_link(get_post_time('Y'), get_post_time('m'))); ?>"><span><?php echo esc_html(get_the_date('j')); ?></span><?php echo esc_html(get_the_date('M, Y')); ?></a>
														</span>
													</div>

													<?php if (has_post_thumbnail()) { ?>
														<figure class="post-thumbnail ">
															<div class="featured-image d-flex align-items-center justify-content-center mt-3">
																<a href="<?php echo esc_url(get_permalink()); ?>" class="post-hover">
																	<?php
																	if (is_single()) {
																		the_post_thumbnail('medium_large');
																	} else {
																		the_post_thumbnail('thumbnail');
																	}

																	?>
																</a>
															</div>
														</figure>
													<?php } ?>

												</div>

												<div class="post-content-exerpt col-lg-8">

													<?php the_title(sprintf('<h3 class="post-title "><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>'); ?>

													<div class="post-excerpt">

														<?php the_excerpt(); ?>

													</div>

												</div>

											</div>
										</article>

									<?php endwhile; ?>
									<div class="d-flex align-items-center justify-content-center mt-4">
										<!-- Pagination -->
										<?php
										// Previous/next page navigation.
										the_posts_pagination(array(
											'prev_text'          => '<i class="fa fa-angle-double-left"></i>',
											'next_text'          => '<i class="fa fa-angle-double-right"></i>',
										)); ?>
										<!-- Pagination -->
									</div>
								<?php else : ?>
									<?php get_template_part('theme_addons/content/content', 'none'); ?>
								<?php endif; ?>


							</div>
						</div>
					</section>

				</div>
			</div>
		</div>
	</div>


<?php get_footer(); ?>