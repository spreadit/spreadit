<?php
class Sort extends BaseModel
{
    const DAY_SECONDS   = 86400;
    const WEEK_SECONDS  = 604800;
    const MONTH_SECONDS = 2592000;
    const YEAR_SECONDS  = 31536000;

    const TIMEFRAME_COOKIE_NAME = 'posts_sort_timeframe';
    const TIMEFRAME_COOKIE_DEFAULT = 'week';
    const SORTBY_COOKIE_NAME = 'posts_sort_mode';
    const SORTBY_COOKIE_DEFAULT = 'hot';

    const ORDERBY_SQL_NEW = 'posts.id';
    const ORDERBY_SQL_TOP = '(posts.upvotes - posts.downvotes)';
    const ORDERBY_SQL_HOT = '(ROUND(LOG10(GREATEST(ABS((posts.upvotes * 2) - (posts.downvotes * 2)), 1)) * SIGN(posts.upvotes - posts.downvotes) + (posts.created_at - 1404725923) / 1000000, 7))';  
    const ORDERBY_SQL_CONTROVERSIAL = 'CASE 
            WHEN (posts.downvotes > posts.upvotes) THEN ROUND((posts.upvotes / GREATEST(posts.downvotes, 1)) * (posts.upvotes + posts.downvotes), 7)
            ELSE                                        ROUND((posts.downvotes / GREATEST(posts.upvotes, 1)) * (posts.upvotes + posts.downvotes), 7)
        END';
}
