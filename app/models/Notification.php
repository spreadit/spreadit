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

    public $COMMENT_TYPE = 0;
    public $POST_TYPE = 1;
    public $ANNOUNCEMENT_TYPE = 2;
    public $UNREAD = 0;
    public $READ = 1;
    public $PAGE_NOTIFICATION_COUNT = 25;


    public function get()
    {
        return DB::table('notifications')
            ->select('notifications.type', 'notifications.item_id', 'notifications.read', 'notifications.created_at', 'comments.data', 'users.username', 'users.points', 'users.votes')
            ->leftJoin('comments', 'notifications.item_id', '=', 'comments.id')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->where('notifications.user_id', '=', Auth::user()->id)
            ->orderBy('notifications.id', 'desc')
            ->simplePaginate(self::PAGE_NOTIFICATION_COUNT);
    }

    public function getUnreadCount()
    {
        return $this->where('user_id', '=', Auth::user()->id)
            ->where('read', '=', self::UNREAD)
            ->count();
    }

    public function markAllAsRead()
    {
        $this->where('user_id', '=', Auth::user()->id)
            ->where('read', '=', self::UNREAD)
            ->update(['read' => self::READ]);
    }
}
