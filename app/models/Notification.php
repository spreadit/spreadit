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


    public function get()
    {
        return DB::table('notifications')
            ->select('notifications.type', 'notifications.item_id', 'notifications.read', 'notifications.created_at', 'comments.data', 'users.username', 'users.points', 'users.votes')
            ->leftJoin('comments', 'notifications.item_id', '=', 'comments.id')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->where('notifications.user_id', '=', Auth::user()->id)
            ->orderBy('notifications.id', 'desc')
            ->simplePaginate(Constant::NOTIFICATION_PAGE_NOTIFICATION_COUNT);
    }

    public function getUnreadCount()
    {
        return $this->where('user_id', '=', Auth::user()->id)
            ->where('read', '=', Constant::NOTIFICATION_UNREAD)
            ->count();
    }

    public function markAllAsRead()
    {
        $this->where('user_id', '=', Auth::user()->id)
            ->where('read', '=', Constant::NOTIFICATION_UNREAD)
            ->update(['read' => Constant::NOTIFICATION_READ]);
    }
}
