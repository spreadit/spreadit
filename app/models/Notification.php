<?php
class Notification extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    protected $guarded = array('id');

    const COMMENT_TYPE = 0;
    const POST_TYPE = 1;
    const ANNOUNCEMENT_TYPE = 2;
    const UNREAD = 0;
    const READ = 1;
    const PAGE_NOTIFICATION_COUNT = 25;


    public static function get()
    {
        return DB::table('notifications')
            ->select('notifications.type', 'notifications.item_id', 'notifications.read', 'notifications.created_at', 'comments.data', 'users.username')
            ->leftJoin('comments', 'notifications.item_id', '=', 'comments.id')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->where('notifications.user_id', '=', Auth::id())
            ->orderBy('notifications.id', 'desc')
            ->simplePaginate(self::PAGE_NOTIFICATION_COUNT);
    }

    public static function hasUnread()
    {
        return Notification::where('user_id', '=', Auth::id())
            ->where('read', '=', self::UNREAD)
            ->count() > 0; 
    }

    public static function markAllAsRead()
    {
        Notification::where('user_id', '=', Auth::id())
            ->where('read', '=', self::UNREAD)
            ->update(['read' => self::READ]);
    }
}
