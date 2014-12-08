<?php

class Constant
{
    const COMMENT_TYPE = 0;
    const POST_TYPE = 1;
    const SECTION_TYPE = 2;

    const SORT_DAY_SECONDS   = 86400;
    const SORT_WEEK_SECONDS  = 604800;
    const SORT_MONTH_SECONDS = 2592000;
    const SORT_YEAR_SECONDS  = 31536000;

    const SORT_TIMEFRAME_COOKIE_NAME = 'posts_sort_timeframe';
    const SORT_TIMEFRAME_COOKIE_DEFAULT = 'week';
    const SORT_SORTBY_COOKIE_NAME = 'posts_sort_mode';
    const SORT_SORTBY_COOKIE_DEFAULT = 'hot';

    const SORT_ORDERBY_SQL_NEW = 'posts.id';
    const SORT_ORDERBY_SQL_TOP = '(posts.upvotes - posts.downvotes)';
    const SORT_ORDERBY_SQL_HOT = '(ROUND(LOG10(GREATEST(ABS((posts.upvotes * 2) - (posts.downvotes * 2)), 1)) * SIGN(posts.upvotes - posts.downvotes) + (posts.created_at - 1404725923) / 1000000, 7))';  
    const SORT_ORDERBY_SQL_CONTROVERSIAL = 'CASE 
            WHEN (posts.downvotes > posts.upvotes) THEN ROUND((posts.upvotes / GREATEST(posts.downvotes, 1)) * (posts.upvotes + posts.downvotes), 7)
            ELSE                                        ROUND((posts.downvotes / GREATEST(posts.upvotes, 1)) * (posts.upvotes + posts.downvotes), 7)
        END';

    const VOTE_UP = 1;
    const VOTE_DOWN = -1;
    const VOTE_VOTES_PAGE_RESULTS = 25;
    const VOTE_COMMENT_PAGE_RESULTS = 25;

    const COMMENT_NO_PARENT = 0;
    const COMMENT_CACHE_PATH_DATA_FROM_ID_MINS = Constant::SORT_YEAR_SECONDS;
    const COMMENT_CACHE_PATH_DATA_FROM_ID_NAME = 'comment_path_from_id_';
    const COMMENT_CACHE_NEWLIST_MINS = 1;
    const COMMENT_CACHE_NEWLIST_NAME = 'comment_newlist_id_';

    const COMMENT_MAX_MARKDOWN_LENGTH = 4000;
    const COMMENT_MAX_COMMENTS_TIMEOUT_SECONDS = 86400;

    const NOTIFICATION_COMMENT_TYPE = 0;
    const NOTIFICATION_POST_TYPE = 1;
    const NOTIFICATION_ANNOUNCEMENT_TYPE = 2;
    const NOTIFICATION_UNREAD = 0;
    const NOTIFICATION_READ = 1;
    const NOTIFICATION_PAGE_NOTIFICATION_COUNT = 25;

    const USER_PAGE_RESULTS = 25;

    const POST_LINK_POST_TYPE = 0;
    const POST_SELF_POST_TYPE = 1;
    const POST_MAX_TITLE_LENGTH = 128;
    const POST_MAX_URL_LENGTH = 256;
    const POST_MAX_MARKDOWN_LENGTH = 6000;
    const POST_MAX_POSTS_TIMEOUT_SECONDS = 86400;


    const SECTION_PAGE_POST_COUNT = 25;
    const SECTION_SECTION_HRENDER_CACHE_MINS = 15;
    const SECTION_ALL_SECTIONS_TITLE = "all";
    const SECTION_TOPBAR_SECTIONS = 15;
    const SECTION_MAX_TITLE_LENGTH = 24;
    const SECTION_MIN_TITLE_LENGTH = 1;
    const SECTION_PAGINATION_AMOUNT = 30;

    const TAG_UP = 1;
    const TAG_DOWN = -1;
    const TAG_NSFW = 0;
    const TAG_NSFL = 1;
}