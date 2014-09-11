Until we have a decent test suite ensure these things work before pushing.
(Also, if you are to make some tests this might help). 

Ensure routes work
    /
    /s/{spreadit}
    /s/{spreadit}/top
    /s/{spreadit}/posts/{post_id}
    /comment/{id}
    /notifications
    
Ensure registration works
Ensure logging in works

As a logged in user:
    Ensure voting for:
        * Sections
        * Posts
        * Comments
        works and adds appropriate points

    Ensure leaving comments work:
        * Reply to another user
        * Reply to post

    Ensure posting works:
        * To new spreadit
        * To existing spreadit
        * To .gifs (check gifycatting)
        * To youtube (gets thumbs)


As a logged out user
    Ensure captcha works
    Ensure commenting works
    Ensure posting to new and old spreadit works
