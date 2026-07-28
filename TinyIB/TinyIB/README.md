# TinyIB - Lightweight and efficient [imageboard](https://en.wikipedia.org/wiki/Imageboard)
[![Donate](https://img.shields.io/liberapay/receives/rocket9labs.com.svg?logo=liberapay)](https://liberapay.com/rocket9labs.com)

This package is a PHP 8.5 modernization of TinyIB. It is intentionally
SQLite3-only and English-only; database migration, alternate database drivers,
the bundled gettext loader, and the web-based Git updater have been removed.

TinyIB is in maintenance mode. Use [Sriracha](https://codeberg.org/tslocum/sriracha) instead.

## Maintenance Mode

All desired features have been implemented in TinyIB. Only security fixes, bug
fixes and translation updates will continue to be added.

[Sriracha](https://codeberg.org/tslocum/sriracha) is a modern imageboard system
with support for [importing TinyIB posts](https://codeberg.org/tslocum/sriracha/src/branch/main/MANUAL.md#import-posts-from-tinyib)
and many additional features. While TinyIB will continue to function, site
administrators are recommended to migrate to Sriracha if and when possible.

## Features

A [**read-only demo**](https://tinyib.rocket9labs.com) is available.

Posts, accounts, bans, reports, keywords, and moderation logs are stored in one
[SQLite](https://sqlite.org) database.

**Not looking for an image board script?**  TinyIB is able to allow new threads without requiring an image, or disallow images entirely.

 - GIF, JPG, PNG, SWF, MP4 and WebM upload.
 - YouTube, Vimeo and SoundCloud embedding.
 - CAPTCHA:
   - A simple, self-hosted implementation is included.
   - [hCaptcha](https://hcaptcha.com) is supported.
   - [ReCAPTCHA](https://www.google.com/recaptcha/about/) is supported. (But [not recommended](https://nearcyan.com/you-probably-dont-need-recaptcha/))
 - Reference links. `>>###`
 - Fetch new replies automatically. (See `TINYIB_AUTOREFRESH`)
 - Delete posts via password.
 - Report posts.
 - Block keywords.
 - Management panel:
   - Account system:
     - Super administrators (all privileges)
     - Administrators (all privileges except account management)
     - Moderators (only able to sticky threads, lock threads, approve posts and delete posts)
   - Ban offensive/abusive posters across all boards.
   - Post using raw HTML.
   - Upgrade automatically when installed via git.  (Tested on Linux only)
 - [Translations:](https://translate.codeberg.org/projects/tinyib/tinyib/)
   - Catalan, Chinese, Dutch, Finnish, French, German, Indonesian, Italian, Japanese, Korean, Norwegian, Polish, Portuguese, Romanian, Russian, Spanish (Mexico) and Turkish

## Donate

Please consider supporting the continued development of TinyIB.

If you make a donation and there is a certain feature you'd like to see added to
TinyIB, <a href="mailto:trevor@rocket9labs.com">send me an email</a>. I can't
promise that I will implement the feature right away, however I will keep your
support in mind.

- [LiberaPay](https://liberapay.com/rocket9labs.com) (anonymous, no added fees)
- [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TEP9HT98XK7QA)

## Install

 1. Verify the following are installed:
    - [PHP 8.5+](https://php.net)
    - The PHP `sqlite3` and `fileinfo` extensions
    - [GD](https://php.net/gd) when image uploads or the simple CAPTCHA are enabled
    - [cURL](https://www.php.net/manual/en/book.curl.php) for URL embeds, hCaptcha, or reCAPTCHA
    - `mbstring` is recommended for correct multibyte text handling
 2. Extract the archive into the directory that will serve the board.
 3. Configure **settings.php**.
    - Set unique administrator credentials and blank the bootstrap passwords
      after the accounts are created.
    - Set `TINYIB_TRIPSEED` to a long random value before the first request.
      Never change it after the board contains data.
    - `TINYIB_DBPATH` is the SQLite database path and defaults to `.tinyib.db`.
    - To require moderation before displaying posts:
      - Set ``TINYIB_REQMOD`` to ``files`` to require moderation for posts with files attached.
      - Set ``TINYIB_REQMOD`` to ``all`` to require moderation for all posts.
      - Moderate posts by visiting the management panel.
    - To allow video uploads:
      - Ensure your web host is running Linux.
      - Install [ffmpeg](https://ffmpeg.org).  On Ubuntu, run ``sudo apt-get install ffmpeg``.
      - Add desired video file types to ``$tinyib_uploads``.
    - To remove the play icon from .SWF and .WebM thumbnails, delete or rename `video_overlay.png`.
    - To use FFMPEG to create thumbnails:
        - Install FFMPEG and ensure  the ``ffmpeg`` and ``ffprobe`` commands are available.
        - Set ``TINYIB_THUMBNAIL`` to ``ffmpeg``.
    - To use ImageMagick instead of GD when creating thumbnails:
      - Install ImageMagick and ensure that the ``convert`` command is available.
      - Set ``TINYIB_THUMBNAIL`` to ``imagemagick``.
      - **Note:** GIF files will have animated thumbnails, which will often have large file sizes.
 4. [CHMOD](https://en.wikipedia.org/wiki/Chmod) write permissions to these directories:
    - ./ (the directory containing TinyIB)
    - ./src/
    - ./thumb/
    - ./res/
 5. Navigate your browser to **imgboard.php** and the following will take place:
    - The database structure will be created.
    - Directories will be verified to be writable.
    - The board index will be written to ``TINYIB_INDEX``.

## Moderate

 1. If you are not logged in already, log in to the management panel by clicking **[Manage]**.
 2. On the board, tick the checkbox next to one or more offending posts.
 3. Scroll to the bottom of the page.
 4. Click **Delete**.
    - You will be redirected to the management panel.
    - From this page you are able to delete the post(s) and/or ban the author(s).

## Update

 1. Back up `settings.php`, the SQLite database, and uploaded files.
 2. Replace the application files while preserving your configured
    `settings.php`, `TINYIB_TRIPSEED`, database, `src/`, and `thumb/`.
 3. Open the management panel, click **Rebuild All**, and verify the board.

## Support

 1. Ensure you are running the latest version of TinyIB.
 2. Review the [open issues](https://codeberg.org/tslocum/tinyib/issues).
 3. Open a [new issue](https://codeberg.org/tslocum/tinyib/issues/new).

## Contribute

 1. [Fork TinyIB.](https://codeberg.org/tslocum/tinyib/fork)
 2. Commit code changes to your forked repository.
 3. [Submit a pull request.](https://codeberg.org/tslocum/tinyib/pulls)
