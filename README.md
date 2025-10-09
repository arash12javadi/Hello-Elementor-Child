
  <h2>About AJDWP Theme</h2>
  <p><strong>AJDWP Theme</strong> ships with 50+ built-in helpers and 100+ utility functions to speed up site setup, reduce plugin bloat, and keep your admin clean. This page documents all settings, their defaults, and how to use them in templates.</p>

  <hr>

  <h3>Quick Start</h3>
  <ol>
    <li>Go to <em>AJDWP Theme Settings</em> → configure tabs: <strong>General</strong>, <strong>Uploads</strong>, <strong>SEO</strong>, <strong>Roles</strong>, and <strong>Pages</strong>.</li>
    <li>Use the provided toggles to enable features. Save each tab with its own button.</li>
    <li>Optional: Use the shortcode <code>[show_disk_usage_limits]</code> on any page to display per-user disk allocations you set under <em>Uploads</em>.</li>
  </ol>

  <hr>
  <h3>  Dynamic Profile Menu Items</h3>
    <b> Placeholders supported in Appearance → Menus: </b><br>
      1) URL:  https://User_Author_page <br>
        - Logged in  → replaced with current user's author URL<br>
        - Logged out → replaced with login URL (custom if set, else wp-login.php with redirect)<br>
  <br><br>
      <b>2) URL or Title: <br><br>#profile_name# <br>#profile_avatar# <br>#profile_both#</b><br><br>
        - Logged in  → title replaced with user's name/avatar/both; URL replaced with author URL<br>
        - Logged out → item removed<br><br>

  <hr>
  <h3>Navbar Links Names For Not Logged in Users</h3>
  Go to Appearance → Menus, expand any menu item:
  <br>
  - Tick <b>“Hide this item for users who are not logged in”</b> to hide it.
  <br>
  - Or set <b>“Alternative label/URL for logged-out users”</b> to change how it appears for logged-out visitors.
  <br>
  Save the menu. That’s it.<br><br>
  <hr>

  <h3>Where Settings Live</h3>
  <p>All settings are stored in a single option: <code>AJDWP_theme_options</code>. You can read them with:</p>
  <pre><code>$options = get_option('AJDWP_theme_options');</code></pre>

  <hr>

  <h3>Tabs &amp; Options</h3>

  <h4>1) General Settings</h4>
  <ul>
    <li><strong>Show Page or Post Title</strong> (<code>show_page_title</code>) — Toggle the default page/post H1/H2 in your templates.</li>
    <li><strong>Add Like &amp; Follow to Theme</strong> (<code>like_follow_system</code>) — Enables your like/follow UI wherever your theme renders it.</li>
    <li><strong>Post View Counter</strong> (<code>post_views</code>) — Shows/stores views on single posts. Pair with your <code>getPostViews()</code>/<code>setPostViews()</code> functions.</li>
    <li><strong>Page View Counter</strong> (<code>page_views</code>) — Same as above but for single pages.</li>
    <li><strong>Post Publish Date</strong> (<code>post_publish_date</code>) — Display publish date on posts if your template checks this option.</li>
    <li><strong>Page Publish Date</strong> (<code>page_publish_date</code>) — Display publish date on pages.</li>
    <li><strong>Cookie Secure Login</strong> (<code>secure_login</code>) — Turns on secure login cookie handling (serve on HTTPS for best results).</li>
    <li><strong>AJDWP Theme Sidebars</strong> (<code>theme_sidebars</code>) — Registers/enables your theme’s widget areas.</li>
    <li><strong>Hide All Admin Notices</strong> (<code>hide_all_admin_notices</code>) — Suppress noisy admin notices for a cleaner dashboard.</li>
    <li><strong>Restrict Admin Access</strong> (<code>restrict_wp_admin_access</code>) — Gate wp-admin to higher roles or logged-in editors only (your implementation decides).</li>
    <li><strong>Hide Admin Bar</strong> (<code>remove_admin_bar</code>) — Hides the front-end admin toolbar for chosen users/roles.</li>
    <li><strong>Set Author Archive Limit</strong> (<code>set_author_archive_limit</code>) — Apply a posts-per-page limit to author archives.</li>
    <li><strong>Stop Extra Image Sizes</strong> (<code>stop_image_sizes</code>) — Prevents generating nonessential thumbnails to save disk space.</li>
    <li><strong>Load Media Library on Frontend</strong> (<code>enqueue_frontend_media_scripts</code>) — Enqueues WP Media modal/scripts on front-end (for custom upload UIs).</li>
    <li><strong>Custom Menu Link URL</strong> (<code>custom_menu_link</code>) — Enables a custom URL slot you can render in your navbar or header.</li>
    <li><strong>Custom Avatar URL</strong> (<code>custom_avatar_url</code>) — Allow users/authors to provide an external avatar. Example template use:
      <pre><code>$url = get_user_meta(get_the_author_meta('ID'), 'custom_avatar_url', true);</code></pre>
    </li>
    <li><strong>Users see only their own posts</strong> (<code>limit_post_access</code>) — Filters post lists so non-admins see only their content.</li>
    <li><strong>Users see only their own uploaded medias</strong> (<code>limit_media_library_access</code>) — Limits Media Library to the user’s uploads.</li>
    <li><strong>Users see only their own Comments</strong> (<code>limit_author_comments</code>) — Restricts comment views similarly.</li>
    <li><strong>Woocommerce Theme Support</strong> (<code>woocommerce_theme_support</code>) — Adds declared WooCommerce support (gallery zoom, etc.).</li>
    <li><strong>Woocommerce mini cart on Navbar</strong> (<code>woocommerce_mini_cart_on_navbar</code>) — Renders a mini-cart in your nav if WooCommerce is active.</li>
    <li><strong>Custom Excerpt Length</strong> (<code>custom_excerpt_length</code>) — When enabled, shows two fields:
      <ul>
        <li><code>excerpt_author_length</code> (default 50)</li>
        <li><code>excerpt_general_length</code> (default 100)</li>
      </ul>
    </li>
    <li><strong>Redirect Login/logout Page</strong> (<code>redirect_login_page</code>) — When enabled, shows:
      <ul>
        <li><code>login_page_url</code> — Custom login URL (defaults to <code>home_url()</code>).</li>
      </ul>
    </li>
  </ul>

  <h4>2) Upload Restrictions</h4>
  <p>These controls help you stay within hosting limits and keep uploads tidy.</p>
  <ul>
    <li><strong>Media Upload Settings</strong> (<code>limit_uploads</code>) — Master toggle. When on, you’ll see:
      <ul>
        <li><strong>Allocated Disk Space (MB) per role</strong>:
          <ul>
            <li><code>editor_disk_usage_limit</code> (default 100)</li>
            <li><code>author_disk_usage_limit</code> (default 20)</li>
            <li><code>contributor_disk_usage_limit</code> (default 10)</li>
            <li><code>subscriber_disk_usage_limit</code> (default 2)</li>
          </ul>
        </li>
        <li><strong>Max Upload Size (KB)</strong> — <code>max_upload_size</code> (default 500)</li>
        <li><strong>Max Image Size (px)</strong> — <code>max_image_width</code> (1980), <code>max_image_height</code> (1440)</li>
        <li><strong>Min Image Size (px)</strong> — <code>min_image_width</code> (300), <code>min_image_height</code> (300)</li>
        <li><strong>Per-user overrides</strong> — add rows of:
          <ul>
            <li><code>entered_email_for_disk_usage_limit[]</code></li>
            <li><code>entered_amount_for_disk_usage_limit[]</code> (MB)</li>
          </ul>
          Use <code>[show_disk_usage_limits]</code> to list saved overrides on the front-end.
        </li>
      </ul>
    </li>
  </ul>

  <h4>3) SEO Settings</h4>
  <ul>
    <li><strong>Add Meta Keywords Field</strong> (<code>add_meta_keywords</code>) — Adds a “keywords” meta box to posts/pages (you render/save meta accordingly).</li>
    <li><strong>Add Meta Descriptions Field</strong> (<code>add_meta_descriptions</code>) — Adds a “description” meta box.</li>
    <li><strong>Google Tag Manager</strong> (<code>google_tag_manager</code>) — Shows two textareas:
      <ul>
        <li><code>gtm_header_script</code> — Paste your &lt;head&gt; snippet.</li>
        <li><code>gtm_body_script</code> — Paste your &lt;body&gt; snippet.</li>
      </ul>
      Scripts are stored safely via <code>wp_kses_post()</code>; your theme should echo them in the right hooks.
    </li>
  </ul>

  <h4>4) Role Settings</h4>
  <p>Fine-tune capabilities for non-admin users (good for UGC sites or editorial teams).</p>
  <ul>
    <li><strong>Contributor Upload Capability</strong> (<code>contributor_can_upload</code>) — Allow Contributors to upload media.</li>
    <li><strong>Contributor Post Capability</strong> (<code>contributor_can_post</code>) — Allow Contributors to create posts (status depends on your flow).</li>
    <li><strong>Subscriber Upload Capability</strong> (<code>subscriber_can_upload</code>) — Allow Subscribers to upload media (default ON).</li>
    <li><strong>Subscriber Post Capability</strong> (<code>subscriber_can_post</code>) — Allow Subscribers to create posts.</li>
  </ul>

  <h4>5) Pages</h4>
  <p>Create required pages with one click. The tab shows whether each page exists and offers action buttons.</p>
  <ul>
    <li><strong>User Profile Pages</strong> (Login/Register/Password Recovery) — Creates pages for user flows (link target: <code>?user_profile_pages=true</code>).</li>
    <li><strong>User Dashboard Pages</strong> — <em>My Comments</em>, <em>My Posts</em>, <em>My Media</em> (link target: <code>?user_dash_pages=true</code>).</li>
    <li><strong>Privacy Notice</strong> — Creates <code>/privacy-notice/</code> and injects sample policies (link target: <code>?privacy_notice_page=true</code>).</li>
    <li><strong>Cookies</strong> — Loads cookie settings UI from:
      <code>theme_addons/cookie_policy/cookie_policy_settings.php</code>.
    </li>
  </ul>

  <hr>

  <h3>How the View Counter Works (Posts &amp; Pages)</h3>
  <p>Enable counters under <em>General</em> → <strong>Post View Counter</strong> and/or <strong>Page View Counter</strong>. Example template gate:</p>
  <p>Increment once per single view (e.g. via <code>template_redirect</code>) using your <code>setPostViews()</code>.</p>

  <hr>

  <h3>Admin UX Helpers Included</h3>
  <ul>
    <li><strong>Tabbed settings UI</strong> with dedicated save buttons per tab.</li>
    <li><strong>Role comparison popup</strong> (via localized <code>roles-comparison-table.html</code>).</li>
    <li><strong>Admin CSS/JS</strong> enqueued from <code>theme_addons/ajdwp_theme_settings/</code>.</li>
  </ul>

  <hr>

  <h3>Developer Notes</h3>
  <ul>
    <li>All checkboxes are normalized in <code>AJDWP_theme_options_validate()</code> so missing keys become <code>0</code>.</li>
    <li>Numbers (limits, sizes, dimensions) are cast to integers.</li>
    <li>GTM scripts are stored with <code>wp_kses_post()</code> to allow safe HTML.</li>
    <li>Add new settings by registering a field (tab/section), rendering a control, sanitizing in the validator, and (optionally) setting a default.</li>
  </ul>

  <hr>

  <h3>Troubleshooting</h3>
  <ul>
    <li><strong>Option not saving?</strong> Make sure its key is handled in the validator and included in defaults if you expect a prefilled value.</li>
    <li><strong>Control not appearing?</strong> Confirm the field is registered to the correct <code>page</code> slug:
      <code>AJDWP_Theme_Options_[general|uploads|seo|roles]</code>.</li>
    <li><strong>Scripts not running?</strong> Check <code>admin_enqueue_scripts</code> is hooked and paths are correct.</li>
  </ul>

  <p><em>Version:</em> 1.0 • <em>Option key:</em> <code>AJDWP_theme_options</code></p>
