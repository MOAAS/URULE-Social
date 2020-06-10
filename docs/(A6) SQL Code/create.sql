DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    location TEXT,
    name TEXT NOT NULL,
    user_search TSVECTOR,
    birthday DATE CHECK(birthday BETWEEN '1900-01-01' AND NOW())
);


DROP TABLE IF EXISTS friend CASCADE;

CREATE TABLE friend (
    user_from INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    user_to INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    PRIMARY KEY(user_from, user_to),
    CONSTRAINT not_friend_with_self CHECK (user_from != user_to)
);


DROP TABLE IF EXISTS friend_request CASCADE;

CREATE TABLE friend_request (    
    user_from INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    user_to INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    request_date TIMESTAMP NOT NULL,
    PRIMARY KEY(user_from, user_to),
    CONSTRAINT not_friend_with_self CHECK (user_from != user_to)
);


DROP TABLE IF EXISTS group_of_friends CASCADE;

CREATE TABLE group_of_friends (
    group_id SERIAL PRIMARY KEY,
    owner_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE NOT NULL,
    name TEXT NOT NULL,
    UNIQUE (owner_id, name)
);


DROP TABLE IF EXISTS group_member CASCADE;

CREATE TABLE group_member (
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    group_id INTEGER REFERENCES group_of_friends(group_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, group_id)
);


DROP TABLE IF EXISTS content CASCADE;

CREATE TABLE content (
    content_id SERIAL PRIMARY KEY,
    author_id INTEGER REFERENCES users(user_id) ON DELETE SET NULL,
    content TEXT NOT NULL,
    content_date TIMESTAMP NOT NULL DEFAULT NOW(),
    likes INTEGER DEFAULT 0,
    dislikes INTEGER DEFAULT 0
);


DROP TABLE IF EXISTS post CASCADE;

CREATE TABLE post (
    post_id INTEGER PRIMARY KEY REFERENCES content(content_id) ON DELETE CASCADE,
    private BOOLEAN DEFAULT FALSE,
    comments INTEGER DEFAULT 0
);


DROP TABLE IF EXISTS comment CASCADE;

CREATE TABLE comment (
    comment_id INTEGER PRIMARY KEY REFERENCES content(content_id) ON DELETE CASCADE,
    post_id INTEGER REFERENCES post(post_id) ON DELETE CASCADE
);


DROP TABLE IF EXISTS comment_rule CASCADE;

CREATE TABLE comment_rule (
    rule_id SERIAL PRIMARY KEY,
    rule_description TEXT NOT NULL,
    rule TEXT NOT NULL
);


DROP TABLE IF EXISTS post_rule CASCADE;

CREATE TABLE post_rule (
    rule_id INTEGER REFERENCES comment_rule(rule_id) ON DELETE CASCADE, 
    post_id INTEGER REFERENCES post(post_id) ON DELETE CASCADE,
    PRIMARY KEY (rule_id, post_id)
);


DROP TABLE IF EXISTS appraisal CASCADE;

CREATE TABLE appraisal (
    appraisal_id SERIAL PRIMARY KEY,
    content_id INTEGER REFERENCES content(content_id) ON DELETE CASCADE NOT NULL,
    user_id INTEGER REFERENCES users(user_id) ON DELETE SET NULL,
    "like" BOOLEAN DEFAULT True,
    UNIQUE (user_id, content_id)
);


DROP TABLE IF EXISTS content_report CASCADE;

CREATE TABLE content_report (
    content_id INTEGER REFERENCES content(content_id) ON DELETE CASCADE,
    reporter_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    date_of_report TIMESTAMP DEFAULT NOW() NOT NULL,
    PRIMARY KEY (content_id, reporter_id)
);


DROP TABLE IF EXISTS notification CASCADE;

CREATE TABLE notification (
    notification_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE NOT NULL ,
    date_of_notification TIMESTAMP DEFAULT NOW() NOT NULL,
    description TEXT NOT NULL
);

DROP TABLE IF EXISTS notification_user CASCADE;

CREATE TABLE notification_user (
    notification_user_id INTEGER PRIMARY KEY REFERENCES notification(notification_id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE NOT NULL
);


DROP TABLE IF EXISTS notification_content CASCADE;

CREATE TABLE notification_content (
    notification_content_id INTEGER PRIMARY KEY REFERENCES notification(notification_id) ON DELETE CASCADE,
    content_id INTEGER REFERENCES content(content_id) ON DELETE CASCADE NOT NULL
);


DROP TABLE IF EXISTS message CASCADE;

CREATE TABLE message (
    message_id SERIAL PRIMARY KEY,
    sender_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE NOT NULL ,
    receiver_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE NOT NULL,
    date_sent TIMESTAMP DEFAULT NOW() NOT NULL,
    content TEXT NOT NULL,
    seen BOOLEAN DEFAULT false
);


DROP TABLE IF EXISTS admin CASCADE;

CREATE TABLE admin (
    admin_id INTEGER PRIMARY KEY REFERENCES users(user_id) ON DELETE CASCADE
);


DROP TABLE IF EXISTS user_ban CASCADE;

CREATE TABLE user_ban (
    ban_id SERIAL PRIMARY KEY,
    user_banned INTEGER UNIQUE REFERENCES users(user_id) ON DELETE CASCADE NOT NULL,
    banned_by INTEGER REFERENCES admin(admin_id) ON DELETE SET NULL,
    date_of_ban TIMESTAMP DEFAULT NOW() NOT NULL,
    reason_of_ban TEXT
);


DROP TABLE IF EXISTS announcement CASCADE;

CREATE TABLE announcement (
    announcement_id SERIAL PRIMARY KEY,
    author_id INTEGER REFERENCES admin(admin_id) ON DELETE SET NULL,
    date_of_creation TIMESTAMP NOT NULL DEFAULT NOW(),
    duration_secs INTEGER NOT NULL CHECK (duration_secs > 0),
    content TEXT NOT NULL
);

-----------------------------------------
-- VIEWS
-----------------------------------------

DROP MATERIALIZED VIEW IF EXISTS post_comments_view;

CREATE MATERIALIZED VIEW post_comments_view AS
    SELECT post_id, (setweight(to_tsvector('english', content), 'A') || setweight(to_tsvector('english', comment_list), 'B')) AS post_search
    FROM (
        SELECT post_id, string_agg(content, ' ') AS comment_list
        FROM Comment JOIN content ON (comment_id = content_id)
        GROUP BY post_id
    ) AS comment_lists JOIN content ON (post_id = content_id);

-----------------------------------------
-- INDEXES
-----------------------------------------

CREATE INDEX content_date ON content USING btree(content_date);
CREATE INDEX content_likes ON content USING btree(likes);
CREATE INDEX content_author ON content USING btree(author_id);
CREATE INDEX message_sender ON message USING btree(sender_id);
CREATE INDEX message_receiver ON message USING btree(receiver_id);
CREATE INDEX message_date ON message USING btree(date_sent);
CREATE INDEX notification_date ON notification USING btree(date_of_notification);
CREATE INDEX notification_user_id ON notification USING btree(user_id);
CREATE INDEX comment_post_id ON comment USING btree(post_id);
CREATE INDEX search_post_idx ON post_comments_view USING GIST (post_search);
CREATE INDEX search_user_idx ON users USING GIST (user_search);

-----------------------------------------
-- TRIGGERS and UDFs
-----------------------------------------

DROP FUNCTION IF EXISTS update_content_rating() CASCADE;
DROP FUNCTION IF EXISTS update_post_comments() CASCADE;
DROP FUNCTION IF EXISTS no_request_to_friend() CASCADE;
DROP FUNCTION IF EXISTS only_friends_in_group() CASCADE;
DROP FUNCTION IF EXISTS on_friend_removal() CASCADE;
DROP FUNCTION IF EXISTS message_friends_only() CASCADE;
DROP FUNCTION IF EXISTS appraise_private_content() CASCADE;
DROP FUNCTION IF EXISTS comment_check() CASCADE;

DROP FUNCTION IF EXISTS check_disjoint_content() CASCADE;
DROP FUNCTION IF EXISTS check_disjoint_notification() CASCADE;

DROP FUNCTION IF EXISTS user_search_update() CASCADE;


DROP TRIGGER IF EXISTS update_content_rating ON appraisal;
DROP TRIGGER IF EXISTS update_post_comments ON comment;
DROP TRIGGER IF EXISTS no_request_to_friend ON friend_request;
DROP TRIGGER IF EXISTS only_friends_in_group ON group_member;
DROP TRIGGER IF EXISTS on_friend_removal ON friend;
DROP TRIGGER IF EXISTS message_friends_only ON message;
DROP TRIGGER IF EXISTS appraise_private_content ON message;
DROP TRIGGER IF EXISTS comment_check ON appraisal;

DROP TRIGGER IF EXISTS check_disjoint_notification_user ON notification_user;
DROP TRIGGER IF EXISTS check_disjoint_notification_content ON notification_content;
DROP TRIGGER IF EXISTS check_disjoint_post ON post;
DROP TRIGGER IF EXISTS check_disjoint_comment ON comment;

DROP TRIGGER IF EXISTS user_search_update ON users;

--+--------------------------------+
--| Insert/Delete/Update Appraisal |
--+--------------------------------+
CREATE FUNCTION update_content_rating() RETURNS TRIGGER AS
$BODY$
DECLARE
    content_id_triggered INTEGER;
    num_likes INTEGER := 0;
    num_dislikes INTEGER := 0;
BEGIN
    IF TG_OP = 'DELETE' THEN
        content_id_triggered := OLD.content_id;
    ELSE content_id_triggered := NEW.content_id;
    END IF;
        
    SELECT count(*) into num_likes FROM appraisal WHERE "like" = true AND appraisal.content_id = content_id_triggered; 
    SELECT count(*) into num_dislikes FROM appraisal WHERE "like" = false AND appraisal.content_id = content_id_triggered; 
    UPDATE content SET likes = num_likes, dislikes = num_dislikes WHERE content.content_id = content_id_triggered;

    IF TG_OP = 'DELETE' THEN
        RETURN OLD;
    ELSE RETURN NEW;
    END IF;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_content_rating
    AFTER INSERT OR UPDATE OR DELETE ON appraisal
    FOR EACH ROW
    EXECUTE PROCEDURE update_content_rating();

--+----------------------------------+
--| Insert/Delete/Update comment     |
--+----------------------------------+
CREATE FUNCTION update_post_comments() RETURNS TRIGGER AS
$BODY$
DECLARE
    post_id_triggered INTEGER;
    num_comments INTEGER := 0;
BEGIN 
    IF TG_OP = 'DELETE' THEN
        post_id_triggered := OLD.post_id;
    ELSE post_id_triggered := NEW.post_id;
    END IF;
        
    SELECT count(*) into num_comments FROM comment WHERE post_id = post_id_triggered; 
    UPDATE post SET comments = num_comments WHERE post_id = post_id_triggered;

    IF TG_OP = 'DELETE' THEN
        RETURN OLD;
    ELSE RETURN NEW;
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_post_comments
    AFTER INSERT OR UPDATE OR DELETE ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE update_post_comments();

--+-----------------------+
--| On friend request     |
--+-----------------------+
CREATE FUNCTION no_request_to_friend()
  RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT * 
        FROM friend 
        WHERE (user_from = NEW.user_from AND user_to = NEW.user_to) OR (user_from = NEW.user_to AND user_to = NEW.user_from)
    ) THEN
        RAISE EXCEPTION 'Cannot add request to current friend!';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER no_request_to_friend
    BEFORE INSERT OR UPDATE ON friend_request
    FOR EACH ROW
    EXECUTE PROCEDURE no_request_to_friend();

--+-----------------------+
--| On add to group       |
--+-----------------------+
CREATE FUNCTION only_friends_in_group()
  RETURNS TRIGGER AS
$BODY$
DECLARE
   owner_id INTEGER;
BEGIN
    SELECT group_of_friends.owner_id into owner_id FROM group_of_friends WHERE group_id = NEW.group_id;
    IF NOT EXISTS (
        SELECT * 
        FROM friend 
        WHERE (user_from = NEW.user_id AND user_to = owner_id) OR (user_from = owner_id AND user_to = NEW.user_id)
    ) THEN
        RAISE EXCEPTION 'New group member must be a friend of the owner!';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER only_friends_in_group
    BEFORE INSERT OR UPDATE ON group_member
    FOR EACH ROW
    EXECUTE PROCEDURE only_friends_in_group();

--+-----------------------+
--| On friend removal     |
--+-----------------------+
CREATE FUNCTION on_friend_removal() RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE 
    FROM group_member GM USING group_of_friends G
    WHERE 
        GM.group_id = G.group_id AND
        G.owner_id = OLD.user_from AND
        GM.user_id = OLD.user_to;
    DELETE 
    FROM group_member GM USING group_of_friends G
    WHERE 
        GM.group_id = G.group_id AND
        G.owner_id = OLD.user_to AND
        GM.user_id = OLD.user_from;
    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER on_friend_removal
    AFTER DELETE ON friend
    FOR EACH ROW
    EXECUTE PROCEDURE on_friend_removal();

--+-----------------------+
--| On message creation   |
--+-----------------------+
CREATE FUNCTION message_friends_only() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.sender_id != NEW.receiver_id AND NOT EXISTS (
        SELECT * 
        FROM friend 
        WHERE (user_from = NEW.sender_id AND user_to = NEW.receiver_id) OR (user_from = NEW.receiver_id AND user_to = NEW.sender_id)
    ) THEN RAISE EXCEPTION 'Can only message friend or self!';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER message_friends_only
    BEFORE INSERT OR UPDATE ON message
    FOR EACH ROW
    EXECUTE PROCEDURE message_friends_only();



CREATE FUNCTION appraise_private_content() RETURNS TRIGGER AS
$BODY$
DECLARE
	is_private BOOLEAN;
BEGIN
    SELECT private INTO is_private FROM post WHERE post_id = NEW.content_id;
    IF is_private AND NOT EXISTS (
        SELECT * 
        FROM friend JOIN content ON (content.content_id = NEW.content_id AND (friend.user_from = content.author_id or friend.user_to = CONTENT.author_id))
        WHERE (NEW.user_id = user_from or NEW.user_id = user_to) 
    ) THEN RAISE EXCEPTION 'Cannot appraise non-friend post';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER appraise_private_content
    BEFORE INSERT OR UPDATE ON appraisal
    FOR EACH ROW
    EXECUTE PROCEDURE appraise_private_content();

--+-----------------------+
--| On comment creation   |
--+-----------------------+
CREATE FUNCTION comment_check() RETURNS TRIGGER AS
$BODY$
DECLARE
	user_id INTEGER;
	is_private BOOLEAN;
BEGIN
	SELECT content.author_id INTO user_id FROM content WHERE content.content_id = NEW.comment_id;
    SELECT private INTO is_private FROM post WHERE post_id = NEW.post_id;
	IF is_private AND NOT EXISTS (
        SELECT * 
        FROM friend JOIN content ON (content.content_id = NEW.post_id AND (friend.user_from = content.author_id or friend.user_to = CONTENT.author_id))
        WHERE (user_id = user_from or user_id = user_to) 
    ) THEN RAISE EXCEPTION 'Cannot comment on non-friend post';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER comment_check
    BEFORE INSERT OR UPDATE ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE comment_check();

--+-------------------------------+
--| Ensure disjoint notification  |
--+-------------------------------+

CREATE FUNCTION check_disjoint_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_TABLE_NAME = 'notification_user' THEN
        IF EXISTS (SELECT * FROM notification_content WHERE notification_content_id = NEW.notification_user_id) THEN RAISE EXCEPTION 'Notification ID already exists!'; END IF;
    ELSIF TG_TABLE_NAME = 'notification_content' THEN
        IF EXISTS (SELECT * FROM notification_user WHERE notification_user_id = NEW.notification_content_id) THEN RAISE EXCEPTION 'Notification ID already exists!'; END IF;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_disjoint_notification_user
    BEFORE INSERT OR UPDATE ON notification_user
    FOR EACH ROW
    EXECUTE PROCEDURE check_disjoint_notification();

CREATE TRIGGER check_disjoint_notification_content
    BEFORE INSERT OR UPDATE ON notification_content
    FOR EACH ROW
    EXECUTE PROCEDURE check_disjoint_notification();

--+---------------------------+
--| Ensure disjoint content   |
--+---------------------------+

CREATE FUNCTION check_disjoint_content() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_TABLE_NAME = 'post' THEN 
        IF EXISTS (SELECT * FROM comment WHERE comment_id = NEW.post_id) THEN RAISE EXCEPTION 'Content ID already exists!'; END IF;
    ELSIF TG_TABLE_NAME = 'comment' THEN 
        IF EXISTS (SELECT * FROM post WHERE post_id = NEW.comment_id) THEN RAISE EXCEPTION 'Content ID already exists!'; END IF;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER check_disjoint_comment
    BEFORE INSERT OR UPDATE ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE check_disjoint_content();

CREATE TRIGGER check_disjoint_post
    BEFORE INSERT OR UPDATE ON post
    FOR EACH ROW
    EXECUTE PROCEDURE check_disjoint_content();

--+---------------------------+
--| User ts_vectors           |
--+---------------------------+

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS
$BODY$
BEGIN
    NEW.user_search = setweight(to_tsvector('english', NEW.name), 'A') || setweight(to_tsvector('english', NEW.location), 'B');
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
    EXECUTE PROCEDURE user_search_update();