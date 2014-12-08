<?php
class Sort extends BaseModel
{
    public $DAY_SECONDS   = 86400;
    public $WEEK_SECONDS  = 604800;
    public $MONTH_SECONDS = 2592000;
    public $YEAR_SECONDS  = 31536000;

    public $TIMEFRAME_COOKIE_NAME = 'posts_sort_timeframe';
    public $TIMEFRAME_COOKIE_DEFAULT = 'week';
    public $SORTBY_COOKIE_NAME = 'posts_sort_mode';
    public $SORTBY_COOKIE_DEFAULT = 'hot';

    public $ORDERBY_SQL_NEW = 'posts.id';
    public $ORDERBY_SQL_TOP = '(posts.upvotes - posts.downvotes)';
    public $ORDERBY_SQL_HOT = '(ROUND(LOG10(GREATEST(ABS((posts.upvotes * 2) - (posts.downvotes * 2)), 1)) * SIGN(posts.upvotes - posts.downvotes) + (posts.created_at - 1404725923) / 1000000, 7))';  
    public $ORDERBY_SQL_CONTROVERSIAL = 'CASE 
            WHEN (posts.downvotes > posts.upvotes) THEN ROUND((posts.upvotes / GREATEST(posts.downvotes, 1)) * (posts.upvotes + posts.downvotes), 7)
            ELSE                                        ROUND((posts.downvotes / GREATEST(posts.upvotes, 1)) * (posts.upvotes + posts.downvotes), 7)
        END';
}
