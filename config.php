<?php
#############################################
## ResourceSpace Configuration (Env-driven)
##
## Required Environment Variables:
## -----------------------------------------
## RS_DB_HOST            - Database host
## RS_DB_USER            - Database username
## RS_DB_PASSWORD        - Database password
## RS_DB_NAME            - Database name
##
## RS_BASE_URL           - Full base URL of this ResourceSpace instance
##
## RS_SCRAMBLE_KEY       - System scramble key (secure file naming)
## RS_API_SCRAMBLE_KEY   - API-specific scramble key
##
## RS_EMAIL_NOTIFY       - Email address to receive system notifications
## RS_EMAIL_FROM         - Email address used in "From" headers
##
## RS_SMTP_HOST          - SMTP server hostname
## RS_SMTP_PORT          - SMTP server port
## RS_SMTP_USER          - SMTP username
## RS_SMTP_PASS          - SMTP password
## RS_SMTP_SECURE        - SMTP encryption protocol (e.g., "tls", "ssl")
##
## RS_STATICSYNC_DIR     - Path for StaticSync imports (absolute)
##
##
## Optional Environment Variables:
## -----------------------------------------
## RS_APP_NAME           - Application name (default: "ResourceSpace")
## RS_HOMEANIM_FOLDER    - Path to home slideshow folder (default: null)
## RS_SMTP_DEBUG_LVL     - PHPMailer debug level (default: 0)
##
#############################################


function getenv_or_fail($key)
{
    $val = getenv($key);
    if ($val === false) {
        die("Environment variable '{$key}' is not set");
    }
    return $val;
}

function getenv_or_default($key, $default = null)
{
    $val = getenv($key);
    return ($val === false) ? $default : $val;
}

// --- Database settings ---
$mysql_server   = getenv_or_fail('RS_DB_HOST');
$mysql_username = getenv_or_fail('RS_DB_USER');
$mysql_password = getenv_or_fail('RS_DB_PASSWORD');
$mysql_db       = getenv_or_fail('RS_DB_NAME');

// --- Base URL ---
$baseurl = getenv_or_fail('RS_BASE_URL');

// --- Branding ---
$applicationname = getenv_or_default('RS_APP_NAME', 'ResourceSpace');
$homeanim_folder = getenv_or_default('RS_HOMEANIM_FOLDER', null);

// --- Secure keys ---
$scramble_key     = getenv_or_fail('RS_SCRAMBLE_KEY');
$api_scramble_key = getenv_or_fail('RS_API_SCRAMBLE_KEY');

// --- Email settings ---
$email_notify = getenv_or_fail('RS_EMAIL_NOTIFY');
$email_from   = getenv_or_fail('RS_EMAIL_FROM');

// --- SMTP configuration ---
$use_phpmailer = true;
$use_smtp = true;

$smtp_host     = getenv_or_fail('RS_SMTP_HOST');
$smtp_port     = getenv_or_fail('RS_SMTP_PORT');
$smtp_auth     = true;
$smtp_username = getenv_or_fail('RS_SMTP_USER');
$smtp_password = getenv_or_fail('RS_SMTP_PASS');
$smtp_secure   = getenv_or_fail('RS_SMTP_SECURE');
$smtpautotls   = true;

$smtp_debug_lvl = (int) getenv_or_default('RS_SMTP_DEBUG_LVL', 0);

// --- External tools ---
$imagemagick_path = '/usr/bin';
$ghostscript_path = '/usr/bin';
$ffmpeg_path      = '/usr/bin';
$exiftool_path    = '/usr/bin';
$antiword_path    = '/usr/bin';
$pdftotext_path   = '/usr/bin';

// --- System behavior ---
$imagemagick_colorspace = 'sRGB';
$contact_link = false;
$themes_simple_view = true;
$themes_show_background_image = true;
$sort_tabs = false;
$maxyear_extends_current = 5;
$thumbs_display_archive_state = true;
$featured_collection_static_bg = true;
$user_pref_user_management_notifications = true;

$stemming = true;
$case_insensitive_username = true;

$use_zip_extension = true;
$collection_download = true;

$ffmpeg_preview_force = true;
$ffmpeg_preview_extension = 'mp4';
$ffmpeg_preview_options = '-f mp4 -b:v 1200k -b:a 64k -ac 1 -c:v libx264 -pix_fmt yuv420p -profile:v baseline -level 3 -c:a aac -strict -2';

$daterange_search = true;
$upload_then_edit = true;
$comments_resource_enable = true;
$use_native_input_for_date_field = true;
$resource_view_use_pre = true;

$purge_temp_folder_age = 90;
$filestore_evenspread = true;

$file_checksums = true;
$hide_real_filepath = true;

$api_upload_urls = array();

// --- Plugins ---
$plugins[] = 'brand_guidelines';

// --- StaticSync ---
$syncdir = getenv_or_fail('RS_STATICSYNC_DIR');
$staticsync_userref = 1;
$staticsync_ingest = true;
$staticsync_autotheme = false;
$staticsync_defaultstate = -1;
$staticsync_cleanup_empty_dirs = true;

$phpmailer_exception_on_error = true;
$phpmailer_debug = true;

$debug_log = false;
$debug_log_location = '/dev/stdout';

