DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    email TEXT UNIQUE NOT NULL CHECK (char_length(email) <= 255),
    password TEXT NOT NULL,
    location TEXT CHECK (char_length(location) <= 25),
    name TEXT NOT NULL CHECK (char_length(name) <= 255),
    user_search TSVECTOR,
    birthday DATE CHECK(birthday BETWEEN '1900-01-01' AND NOW()),
    remember_token VARCHAR(100),
    google_id VARCHAR(100)
);

DROP TABLE IF EXISTS password_resets CASCADE;

CREATE TABLE password_resets (
    id SERIAL PRIMARY KEY,
    email TEXT NOT NULL,
    token TEXT NOT NULL,
    created_at TIMESTAMP
);


DROP TABLE IF EXISTS friend CASCADE;

CREATE TABLE friend (
    user_from INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    user_to INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    PRIMARY KEY(user_from, user_to),
    UNIQUE (user_to, user_from),
    CONSTRAINT one_way_friendship CHECK (user_from < user_to)
);


DROP TABLE IF EXISTS friend_request CASCADE;

CREATE TABLE friend_request (
    user_from INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    user_to INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    request_date TIMESTAMP NOT NULL,
    PRIMARY KEY(user_from, user_to),
    UNIQUE (user_to, user_from),
    CONSTRAINT not_friend_with_self CHECK (user_from != user_to)
);


DROP TABLE IF EXISTS group_of_friends CASCADE;

CREATE TABLE group_of_friends (
    group_id SERIAL PRIMARY KEY,
    owner_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE NOT NULL,
    group_name TEXT NOT NULL CHECK (char_length(group_name) <= 255),
    UNIQUE (owner_id, group_name)
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
    content TEXT NOT NULL CHECK (char_length(content) <= 1000),
    content_date TIMESTAMP NOT NULL DEFAULT NOW(),
    likes INTEGER DEFAULT 0,
    dislikes INTEGER DEFAULT 0
);

DROP TABLE IF EXISTS post_rule CASCADE;

CREATE TABLE post_rule (
    post_rule_id SERIAL PRIMARY KEY,
    rule_description TEXT NOT NULL CHECK (char_length(rule_description) <= 50),
    rule_json TEXT NOT NULL CHECK (char_length(rule_json) <= 10000),
    error_message TEXT CHECK (char_length(error_message) <= 1000)
);

DROP TABLE IF EXISTS post CASCADE;

CREATE TABLE post (
    post_id INTEGER PRIMARY KEY REFERENCES content(content_id) ON DELETE CASCADE,
    private BOOLEAN DEFAULT FALSE,
    comments INTEGER DEFAULT 0,
    post_rule_id INTEGER REFERENCES post_rule(post_rule_id) ON DELETE SET NULL
);


DROP TABLE IF EXISTS comment CASCADE;

CREATE TABLE comment (
    comment_id INTEGER PRIMARY KEY REFERENCES content(content_id) ON DELETE CASCADE,
    post_id INTEGER REFERENCES post(post_id) ON DELETE CASCADE
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
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    date_of_report TIMESTAMP DEFAULT NOW() NOT NULL,
    PRIMARY KEY (content_id, user_id)
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
    reason_of_ban TEXT CHECK (char_length(reason_of_ban) <= 1000)
);


DROP TABLE IF EXISTS announcement CASCADE;

CREATE TABLE announcement (
    announcement_id SERIAL PRIMARY KEY,
    author_id INTEGER REFERENCES admin(admin_id) ON DELETE SET NULL,
    date_of_creation TIMESTAMP NOT NULL DEFAULT NOW(),
    duration_secs INTEGER NOT NULL CHECK (duration_secs > 0),
    content TEXT NOT NULL CHECK (char_length(content) <= 500)
);

-----------------------------------------
-- VIEWS
-----------------------------------------

DROP MATERIALIZED VIEW IF EXISTS post_comments_view;

CREATE MATERIALIZED VIEW post_comments_view AS
    SELECT post.post_id, (setweight(to_tsvector('english', content), 'A') || setweight(to_tsvector('english', COALESCE(comment_list, '')), 'B')) AS post_search
    FROM post JOIN content ON (post_id = content_id) LEFT JOIN (
        SELECT post_id, string_agg(content, ' ') AS comment_list
        FROM comment JOIN content ON (comment_id = content_id)
        GROUP BY post_id
    ) AS comment_lists ON (post.post_id = comment_lists.post_id);

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

CREATE INDEX password_resets_email ON password_resets USING btree(email);

-----------------------------------------
-- TRIGGERS and UDFs
-----------------------------------------
DROP FUNCTION IF EXISTS can_view_post(INTEGER, INTEGER, BOOLEAN) CASCADE;
DROP FUNCTION IF EXISTS friends_of(INTEGER) CASCADE;

DROP FUNCTION IF EXISTS update_content_rating() CASCADE;
DROP FUNCTION IF EXISTS update_post_comments() CASCADE;
DROP FUNCTION IF EXISTS no_request_to_friend() CASCADE;
DROP FUNCTION IF EXISTS only_friends_in_group() CASCADE;
DROP FUNCTION IF EXISTS on_friend_removal() CASCADE;
DROP FUNCTION IF EXISTS message_friends_only() CASCADE;
DROP FUNCTION IF EXISTS check_view_content_perms() CASCADE;
DROP FUNCTION IF EXISTS comment_check_perms() CASCADE;

DROP FUNCTION IF EXISTS check_disjoint_content() CASCADE;
DROP FUNCTION IF EXISTS check_disjoint_notification() CASCADE;
DROP FUNCTION IF EXISTS on_comment_delete() CASCADE;

DROP FUNCTION IF EXISTS user_search_update() CASCADE;


DROP TRIGGER IF EXISTS update_content_rating ON appraisal;
DROP TRIGGER IF EXISTS update_post_comments ON comment;
DROP TRIGGER IF EXISTS no_request_to_friend ON friend_request;
DROP TRIGGER IF EXISTS only_friends_in_group ON group_member;
DROP TRIGGER IF EXISTS on_friend_removal ON friend;
DROP TRIGGER IF EXISTS message_friends_only ON message;
DROP TRIGGER IF EXISTS appraise_private_content ON appraisal;
DROP TRIGGER IF EXISTS report_private_content ON content_report;
DROP TRIGGER IF EXISTS comment_check_perms ON comment;

DROP TRIGGER IF EXISTS check_disjoint_notification_user ON notification_user;
DROP TRIGGER IF EXISTS check_disjoint_notification_content ON notification_content;
DROP TRIGGER IF EXISTS check_disjoint_post ON post;
DROP TRIGGER IF EXISTS check_disjoint_comment ON comment;
DROP TRIGGER IF EXISTS on_comment_delete ON comment;

DROP TRIGGER IF EXISTS user_search_update ON users;

--+---------------------------+
--| Can view post function    |
--+---------------------------+
CREATE FUNCTION can_view_post(user_id INTEGER, author_id INTEGER, private BOOLEAN)
RETURNS BOOLEAN AS
$BODY$
BEGIN
	IF private = false
        OR author_id = user_id
        OR EXISTS (SELECT * FROM admin WHERE admin_id = user_id)
        OR EXISTS (
            SELECT user_from
            FROM friend
            WHERE (user_from = user_id AND user_to = author_id) OR (user_from = author_id AND user_to = user_id)
        ) THEN RETURN TRUE;
	ELSE RETURN FALSE;
	END IF;
END
$BODY$
LANGUAGE plpgsql;

--+---------------------------+
--| Get friends function      |
--+---------------------------+
CREATE FUNCTION friends_of(user_id INTEGER)
RETURNS TABLE (friend_id INTEGER) AS
$BODY$
BEGIN
    RETURN QUERY
	SELECT user_to AS friend_id FROM friend WHERE user_from = user_id
    UNION
    SELECT user_from AS friend_id FROM friend WHERE user_to = user_id;
END;
$BODY$
LANGUAGE plpgsql;

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
CREATE FUNCTION no_request_to_friend() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT *
        FROM friend
        WHERE (user_from = NEW.user_from AND user_to = NEW.user_to) OR (user_from = NEW.user_to AND user_to = NEW.user_from)
    ) THEN RAISE EXCEPTION 'Cannot add request to current friend!';
    ELSIF EXISTS (SELECT * FROM friend_request WHERE (user_from = NEW.user_to AND user_to = NEW.user_from))
    THEN RAISE EXCEPTION 'Cannot add request to user who already sent one!';
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
CREATE FUNCTION only_friends_in_group() RETURNS TRIGGER AS
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


--+--------------------------------+
--| On appraisal/report creation   |
--+--------------------------------+
CREATE FUNCTION check_view_content_perms() RETURNS TRIGGER AS
$BODY$
DECLARE
    viewed_post_id INTEGER;
	post_author_id INTEGER;
	is_private BOOLEAN;
BEGIN
    IF (TG_OP = 'UPDATE' AND OLD.user_id IS NOT NULL AND NEW.user_id IS NULL)
        THEN RETURN NEW;
    END IF;

    IF EXISTS (SELECT * FROM post WHERE post_id = NEW.content_id)
       THEN SELECT NEW.content_id INTO viewed_post_id;
    ELSE SELECT post_id INTO viewed_post_id FROM comment WHERE comment_id = NEW.content_id;
    END IF;

    SELECT author_id INTO post_author_id FROM content WHERE content_id = viewed_post_id;
    SELECT private INTO is_private FROM post WHERE post_id = viewed_post_id;

    IF (NOT can_view_post(NEW.user_id, post_author_id, is_private))
        THEN RAISE EXCEPTION 'Invalid appraisal/report: User does not have PVW permission.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER appraise_private_content
    BEFORE INSERT OR UPDATE ON appraisal
    FOR EACH ROW
    EXECUTE PROCEDURE check_view_content_perms();

CREATE TRIGGER report_private_content
    BEFORE INSERT OR UPDATE ON content_report
    FOR EACH ROW
    EXECUTE PROCEDURE check_view_content_perms();

--+-----------------------+
--| On comment creation   |
--+-----------------------+
CREATE FUNCTION comment_check_perms() RETURNS TRIGGER AS
$BODY$
DECLARE
	comment_author_id INTEGER;
	post_author_id INTEGER;
	is_private BOOLEAN;
BEGIN
    SELECT author_id INTO comment_author_id FROM content WHERE content_id = NEW.comment_id;
    SELECT author_id INTO post_author_id FROM content WHERE content_id = NEW.post_id;
    SELECT private INTO is_private FROM post WHERE post_id = NEW.post_id;

    IF (NOT can_view_post(comment_author_id, post_author_id, is_private))
        THEN RAISE EXCEPTION 'Invalid comment: User does not have PVW permission.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER comment_check_perms
    BEFORE INSERT OR UPDATE ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE comment_check_perms();

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

--+----------------------------------+
--| Delete content on comment delete |
--+----------------------------------+

CREATE FUNCTION on_comment_delete() RETURNS TRIGGER AS
$BODY$
BEGIN
    DELETE FROM content WHERE content_id = OLD.comment_id;
    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER on_comment_delete
    AFTER DELETE ON comment
    FOR EACH ROW
    EXECUTE PROCEDURE on_comment_delete();


--+---------------------------+
--| User ts_vectors           |
--+---------------------------+

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS
$BODY$
BEGIN
    NEW.user_search = setweight(to_tsvector('english', NEW.name), 'A') || setweight(to_tsvector('english', COALESCE(NEW.location, '')), 'B');
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
    EXECUTE PROCEDURE user_search_update();

-- 25
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('nec.metus.facilisis@nunc.ca','$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W','Helkijn','Rinah Aguilar','1971-04-21');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('mi.lacinia@suscipit.net','$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W','Stockholm','Dale Diaz','1990-11-21');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('facilisis.non@ametconsectetueradipiscing.net','5268810022539256','North Shore','Evan Stout','1957-10-24');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('id.ante@augueutlacus.edu','5377436686389768','Linkebeek','Denise Stephenson','1962-12-31');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('lacus@lacusNulla.edu','5285728733528328','Severodvinsk','Claire Deleon','1956-04-07');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('tempus.non.lacinia@pedesagittis.org','5180683513355614','Chillán Viejo','Hiram Short','1977-04-16');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('lectus.ante@aliquamiaculis.edu','5234817460555919','Puerto Guzmán','Zephr Estes','1993-04-24');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('orci.Phasellus.dapibus@facilisis.co.uk','5363960088031872','Boussu-lez-Walcourt','Sonya Mccoy','1971-04-07');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('adipiscing@elementumpurus.edu','5216252876622958','Dornoch','Kiona Tate','1961-12-11');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('Suspendisse@id.edu','5454336714938576','Basingstoke','Drake Gallegos','1965-01-07');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('Mauris.non.dui@Maurisut.net','5205388965196547','Hantes-WihŽries','Dennis Hartman','1962-11-19');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('quam.Curabitur@vel.org','5393448413737755','Watson Lake','Hyatt Sharpe','1976-10-03');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('Quisque.purus@tellusAeneanegestas.co.uk','5394384605292797','San Fabián','Hollee Perez','1973-07-28');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('Etiam.imperdiet@faucibusid.ca','5482092664080309','Durgapur','Mollie Holmes','1960-06-08');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('Donec.tincidunt@diam.com','5468438168349294','Sainte-Marie-sur-Semois','Graham Chavez','1995-03-26');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('Vestibulum.ante@volutpatNulladignissim.org','5498299051535975','Bastogne','Reuben Stanton','1971-05-04');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('egestas.Aliquam.nec@velturpis.edu','5338648054038086','Münster','Mira Adams','1973-05-25');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('habitant@Sed.net','5237757299550201','Zaltbommel','Branden Larsen','1991-10-05');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('vel.quam@Morbiquis.net','5241067230759091','Söderhamn','Leo Molina','1993-11-10');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('neque.Morbi@sociisnatoquepenatibus.net','5253614621554907','Lochranza','Mollie Baker','1986-10-06');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('vitae.dolor@facilisislorem.edu','5519837170417628','Chhindwara','Drake Humphrey','1989-04-20');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('gravida.non.sollicitudin@rhoncus.edu','5109323500896175','Adoni','Chaney Simpson','1965-08-09');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('Integer.eu.lacus@eterosProin.org','5547041026552441','Fresia','Scott Park','1963-06-15');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('mauris.elit.dictum@eratEtiamvestibulum.com','5465563181880562','Oyace','Elvis Randall','1965-02-16');
INSERT INTO "users" (email,password,location,name,birthday) VALUES ('ligula@Mauris.edu','5435500659127370','Wollongong','Cynthia Velasquez','1983-08-15');


-- ~100
INSERT INTO "friend" (user_from,user_to) VALUES (1,2);
INSERT INTO "friend" (user_from,user_to) VALUES (1,3);
INSERT INTO "friend" (user_from,user_to) VALUES (1,4);
INSERT INTO "friend" (user_from,user_to) VALUES (1,6);
INSERT INTO "friend" (user_from,user_to) VALUES (1,8);
INSERT INTO "friend" (user_from,user_to) VALUES (1,11);
INSERT INTO "friend" (user_from,user_to) VALUES (1,17);
INSERT INTO "friend" (user_from,user_to) VALUES (1,20);
INSERT INTO "friend" (user_from,user_to) VALUES (1,22);
INSERT INTO "friend" (user_from,user_to) VALUES (2,4);
INSERT INTO "friend" (user_from,user_to) VALUES (2,7);
INSERT INTO "friend" (user_from,user_to) VALUES (2,9);
INSERT INTO "friend" (user_from,user_to) VALUES (2,11);
INSERT INTO "friend" (user_from,user_to) VALUES (2,20);
INSERT INTO "friend" (user_from,user_to) VALUES (2,25);
INSERT INTO "friend" (user_from,user_to) VALUES (3,5);
INSERT INTO "friend" (user_from,user_to) VALUES (3,7);
INSERT INTO "friend" (user_from,user_to) VALUES (3,8);
INSERT INTO "friend" (user_from,user_to) VALUES (3,10);
INSERT INTO "friend" (user_from,user_to) VALUES (3,11);
INSERT INTO "friend" (user_from,user_to) VALUES (3,13);
INSERT INTO "friend" (user_from,user_to) VALUES (3,16);
INSERT INTO "friend" (user_from,user_to) VALUES (3,17);
INSERT INTO "friend" (user_from,user_to) VALUES (3,24);
INSERT INTO "friend" (user_from,user_to) VALUES (3,25);
INSERT INTO "friend" (user_from,user_to) VALUES (4,5);
INSERT INTO "friend" (user_from,user_to) VALUES (4,13);
INSERT INTO "friend" (user_from,user_to) VALUES (4,18);
INSERT INTO "friend" (user_from,user_to) VALUES (4,20);
INSERT INTO "friend" (user_from,user_to) VALUES (4,25);
INSERT INTO "friend" (user_from,user_to) VALUES (5,10);
INSERT INTO "friend" (user_from,user_to) VALUES (5,12);
INSERT INTO "friend" (user_from,user_to) VALUES (5,14);
INSERT INTO "friend" (user_from,user_to) VALUES (5,17);
INSERT INTO "friend" (user_from,user_to) VALUES (6,11);
INSERT INTO "friend" (user_from,user_to) VALUES (6,12);
INSERT INTO "friend" (user_from,user_to) VALUES (6,14);
INSERT INTO "friend" (user_from,user_to) VALUES (6,22);
INSERT INTO "friend" (user_from,user_to) VALUES (6,24);
INSERT INTO "friend" (user_from,user_to) VALUES (6,25);
INSERT INTO "friend" (user_from,user_to) VALUES (7,9);
INSERT INTO "friend" (user_from,user_to) VALUES (7,12);
INSERT INTO "friend" (user_from,user_to) VALUES (7,15);
INSERT INTO "friend" (user_from,user_to) VALUES (7,17);
INSERT INTO "friend" (user_from,user_to) VALUES (7,20);
INSERT INTO "friend" (user_from,user_to) VALUES (7,22);
INSERT INTO "friend" (user_from,user_to) VALUES (7,23);
INSERT INTO "friend" (user_from,user_to) VALUES (8,10);
INSERT INTO "friend" (user_from,user_to) VALUES (8,11);
INSERT INTO "friend" (user_from,user_to) VALUES (8,23);
INSERT INTO "friend" (user_from,user_to) VALUES (9,17);
INSERT INTO "friend" (user_from,user_to) VALUES (9,23);
INSERT INTO "friend" (user_from,user_to) VALUES (9,25);
INSERT INTO "friend" (user_from,user_to) VALUES (10,14);
INSERT INTO "friend" (user_from,user_to) VALUES (10,15);
INSERT INTO "friend" (user_from,user_to) VALUES (10,17);
INSERT INTO "friend" (user_from,user_to) VALUES (10,18);
INSERT INTO "friend" (user_from,user_to) VALUES (11,20);
INSERT INTO "friend" (user_from,user_to) VALUES (11,22);
INSERT INTO "friend" (user_from,user_to) VALUES (11,23);
INSERT INTO "friend" (user_from,user_to) VALUES (11,24);
INSERT INTO "friend" (user_from,user_to) VALUES (11,25);
INSERT INTO "friend" (user_from,user_to) VALUES (12,14);
INSERT INTO "friend" (user_from,user_to) VALUES (12,15);
INSERT INTO "friend" (user_from,user_to) VALUES (12,17);
INSERT INTO "friend" (user_from,user_to) VALUES (12,18);
INSERT INTO "friend" (user_from,user_to) VALUES (12,19);
INSERT INTO "friend" (user_from,user_to) VALUES (12,24);
INSERT INTO "friend" (user_from,user_to) VALUES (13,15);
INSERT INTO "friend" (user_from,user_to) VALUES (14,16);
INSERT INTO "friend" (user_from,user_to) VALUES (14,19);
INSERT INTO "friend" (user_from,user_to) VALUES (14,20);
INSERT INTO "friend" (user_from,user_to) VALUES (14,23);
INSERT INTO "friend" (user_from,user_to) VALUES (15,16);
INSERT INTO "friend" (user_from,user_to) VALUES (15,18);
INSERT INTO "friend" (user_from,user_to) VALUES (15,19);
INSERT INTO "friend" (user_from,user_to) VALUES (15,24);
INSERT INTO "friend" (user_from,user_to) VALUES (15,25);
INSERT INTO "friend" (user_from,user_to) VALUES (16,17);
INSERT INTO "friend" (user_from,user_to) VALUES (16,18);
INSERT INTO "friend" (user_from,user_to) VALUES (16,23);
INSERT INTO "friend" (user_from,user_to) VALUES (17,22);
INSERT INTO "friend" (user_from,user_to) VALUES (17,24);
INSERT INTO "friend" (user_from,user_to) VALUES (17,25);
INSERT INTO "friend" (user_from,user_to) VALUES (18,24);
INSERT INTO "friend" (user_from,user_to) VALUES (18,25);
INSERT INTO "friend" (user_from,user_to) VALUES (19,20);
INSERT INTO "friend" (user_from,user_to) VALUES (19,23);
INSERT INTO "friend" (user_from,user_to) VALUES (20,22);
INSERT INTO "friend" (user_from,user_to) VALUES (21,24);
INSERT INTO "friend" (user_from,user_to) VALUES (22,25);
INSERT INTO "friend" (user_from,user_to) VALUES (23,24);

-- 15 por datas certas
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (4,15,'2019-09-01');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (4,17,'2019-03-12');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (6,2,'2019-05-22');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (6,3,'2019-07-22');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (7,21,'2020-02-07');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (12,1,'2019-05-09');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (14,4,'2020-01-12');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (14,17,'2019-03-20');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (15,1,'2019-08-31');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (15,2,'2019-04-16');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (15,6,'2019-06-08');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (19,16,'2019-08-22');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (20,16,'2019-07-28');
INSERT INTO "friend_request" (user_from,user_to,request_date) VALUES (25,20,'2019-04-08');

--25
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (2,'the best ones');  --1
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (2,'work friends');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (3,'gamers');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (4,'boring people');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (5,'close friends');  --5
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (7,'dem boys');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (7,'work friends');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (8,'close friends');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (10,'Family');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (10,'main group');   --10
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (11,'school');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (12,'gamer friends');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (14,'family and close friends');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (15,'smash');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (15,'yeyy');      --15
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (16,'classmates');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (17,'school');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (19,'work friends');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (22,'colleagues');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (22,'group1');        --20
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (24,'school');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (24,'the boysss');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (24,'uwu');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (25,'close');
INSERT INTO "group_of_friends" (owner_id,group_name) VALUES (25,'testgroup');     --25

--50
INSERT INTO "group_member" (user_id,group_id) VALUES (4,1);
INSERT INTO "group_member" (user_id,group_id) VALUES (7,1);
INSERT INTO "group_member" (user_id,group_id) VALUES (9,2);
INSERT INTO "group_member" (user_id,group_id) VALUES (24,3);
INSERT INTO "group_member" (user_id,group_id) VALUES (17,3);
INSERT INTO "group_member" (user_id,group_id) VALUES (16,3);
INSERT INTO "group_member" (user_id,group_id) VALUES (1,4);
INSERT INTO "group_member" (user_id,group_id) VALUES (2,4);
INSERT INTO "group_member" (user_id,group_id) VALUES (10,5);
INSERT INTO "group_member" (user_id,group_id) VALUES (14,5);
INSERT INTO "group_member" (user_id,group_id) VALUES (2,6);
INSERT INTO "group_member" (user_id,group_id) VALUES (3,7);
INSERT INTO "group_member" (user_id,group_id) VALUES (9,7);
INSERT INTO "group_member" (user_id,group_id) VALUES (20,7);
INSERT INTO "group_member" (user_id,group_id) VALUES (23,8);
INSERT INTO "group_member" (user_id,group_id) VALUES (10,8);
INSERT INTO "group_member" (user_id,group_id) VALUES (17,9);
INSERT INTO "group_member" (user_id,group_id) VALUES (18,9);
INSERT INTO "group_member" (user_id,group_id) VALUES (14,9);
INSERT INTO "group_member" (user_id,group_id) VALUES (15,10);
INSERT INTO "group_member" (user_id,group_id) VALUES (3,10);
INSERT INTO "group_member" (user_id,group_id) VALUES (25,11);
INSERT INTO "group_member" (user_id,group_id) VALUES (24,12);
INSERT INTO "group_member" (user_id,group_id) VALUES (23,13);
INSERT INTO "group_member" (user_id,group_id) VALUES (5,13);
INSERT INTO "group_member" (user_id,group_id) VALUES (10,13);
INSERT INTO "group_member" (user_id,group_id) VALUES (24,14);
INSERT INTO "group_member" (user_id,group_id) VALUES (18,14);
INSERT INTO "group_member" (user_id,group_id) VALUES (18,15);
INSERT INTO "group_member" (user_id,group_id) VALUES (7,15);
INSERT INTO "group_member" (user_id,group_id) VALUES (14,16);
INSERT INTO "group_member" (user_id,group_id) VALUES (1,17);
INSERT INTO "group_member" (user_id,group_id) VALUES (12,18);
INSERT INTO "group_member" (user_id,group_id) VALUES (15,18);
INSERT INTO "group_member" (user_id,group_id) VALUES (25,19);
INSERT INTO "group_member" (user_id,group_id) VALUES (6,20);
INSERT INTO "group_member" (user_id,group_id) VALUES (7,20);
INSERT INTO "group_member" (user_id,group_id) VALUES (3,21);
INSERT INTO "group_member" (user_id,group_id) VALUES (6,21);
INSERT INTO "group_member" (user_id,group_id) VALUES (23,22);
INSERT INTO "group_member" (user_id,group_id) VALUES (21,22);
INSERT INTO "group_member" (user_id,group_id) VALUES (3,23);
INSERT INTO "group_member" (user_id,group_id) VALUES (2,24);
INSERT INTO "group_member" (user_id,group_id) VALUES (9,25);


-- 50 por datas certas
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (2,'I would be delighted if the sea were full of cucumber juice.','2020-02-02T14:36:16-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (3,'If any cop asks you where you were, just say you were visiting Kansas.','2020-03-24T22:49:05-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (15,'Everyone was busy, so I went to the movie alone.','2020-03-22T22:45:32-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (23,'I am never at home on Sundays.','2020-01-25T12:21:30-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (11,'My dentist tells me that chewing bricks is very bad for your teeth. Who would have guessed xd','2020-03-15T07:02:18-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (9,'It was a really good Monday for being a Saturday.','2019-03-29T22:39:08-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (4,'David subscribes to the "stuff your tent into the bag" strategy over nicely folding it.','2019-08-16T09:08:27-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (9,'You people are crazy.','2020-03-13T18:45:34-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (17,'Any one up for a game?','2019-07-23T02:32:39-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (24,'Goodbye, March Mustache...','2019-04-13T14:36:37-07:00',0,0); --10
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (5,'There are few things better in life than a slice of pie.','2019-09-02T03:08:51-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (21,'Getting up at dawn is for the birds.','2020-02-29T22:29:29-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (2,'YES FINNALY HAPPENED','2020-02-20T10:22:35-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (15,'It is not often you find a soggy banana on the street.','2020-01-28T12:45:26-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (24,'Going to go to the hospital in an hour to finally get stitches for my leg injury. I have an appointment, so hopefully its quick.','2019-11-13T15:35:20-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (4,'2020 will be the best year, calling it right now :) LETS GO','2019-10-02T02:59:12-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (2,'My proposal to the politically correct Automobile Companies would lower the average price of a car to consumers by more than $3500, while at the same time making the cars substantially safer. Engines would run smoother. Positive impact on the environment! Foolish executives!','2019-04-05T12:30:39-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (23,'When the virus subsides, humanity, finally realizing the harm and folly of national, racial and religious divisions, will overcome its innate egotism and truly, fully unite to save the planet and attain a higher level of spiritual consciousness.','2020-01-09T23:26:14-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (23,'You cant compare apples and oranges, but what about bananas and plantains?','2019-04-07T00:20:09-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (8,'I dont like apple juice at all :(','2019-10-23T17:07:53-07:00',0,0); --20
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (24,'You heard it here third. #RickandMorty returns May 3 on adultswim','2019-05-23T02:25:52-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (21,'Even though he thought the world was flat he didn’t see the irony of wanting to travel around the world.','2019-05-19T13:15:27-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (14,'Finally now everyone can represent','2020-01-03T07:37:29-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (3,'I currently have 4 windows open up… and I don’t know why.','2019-09-12T15:14:45-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (10,'My university is raising tuition on foreign students in order to offset being made to lower it for others.','2019-12-24T22:02:01-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (20,'Whats your favorite game?','2020-03-20T22:54:17-07:00',0,0);--26
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (24,'Never underestimate the willingness of the greedy to throw you under the bus.','2019-09-13T06:47:47-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (18,'Dan took the deep dive down the rabbit hole.','2020-01-08T09:30:10-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (4,'Rikers Inmates Punished After Guards Catch Them Trying To Make Bootleg Coronavirus Vaccine','2019-04-20T02:49:36-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (9,'My new film, “I Broke My Leg and Bedazzled It,” has a star-studded cast.','2019-07-30T10:58:10-07:00',0,0); --30
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (10,'It’s crazy that when I go out for groceries I feel like I could be risking my life lol','2019-02-28T07:45:51-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (14,'Describe yourself in 1 word. GO','2019-08-18T22:53:21-07:00',0,0);--32
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (11,'I bet you cant make a sentence without "a"','2019-06-02T07:36:23-07:00',0,0);--33
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (15,'First victory royalle 8)','2020-02-10T03:00:58-08:00',0,0);--34
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (10,'Feeling really bad rn','2019-11-21T00:19:22-08:00',0,0);--35
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (8,'ayyyy we did it','2020-03-06T02:15:16-08:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (15,'Whats your favorite color guys? xD','2019-12-08T08:36:34-08:00',0,0);--37
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (7,'Imo instead of making parry reflect projectiles, just allow us to act the frame after we parry a projectile instead of trapping us in lag','2019-09-28T03:06:09-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (10,'WE GOT THE NEW HOUSSSEEE OMFFFGGGGGGG','2019-03-20T23:50:23-07:00',0,0);
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (6,'When people keep saying lets go but wont tell me where were going','2019-10-02T10:20:11-07:00',0,0); --40
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (1,'VVVVVV','2020-05-08T20:36:02-07:00',0,0);      -->26
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (5,'F1 2014','2020-03-11T04:13:44-07:00',0,0);      -->26
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (16,'gamer','2019-12-30T03:56:20-08:00',0,0);     -->32
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (11,'humble','2019-07-07T12:14:36-07:00',0,0);     -->32
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (24,'You thought you did something here didnt you? Well sorry to burst your bubble but numerous sentences could be constructed without employing the first letter of the English lexicon','2020-10-02T22:23:23-07:00',0,0);     -->33
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (11,'good job son','2019-08-30T00:56:15-07:00',0,0);     -->34
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (2,'Dont worry everything will be fine :)','2020-05-21T16:13:38-07:00',0,0);      -->35
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (11,'Just be happy lmao','2019-09-07T16:38:22-07:00',0,0);     -->35
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (18,'Cyan','2021-02-15T22:27:49-08:00',0,0);     -->37
INSERT INTO "content" (author_id,content,content_date,likes,dislikes) VALUES (17,'Gray','2020-01-02T00:41:03-08:00',0,0);     -->37

--5
INSERT INTO post_rule (rule_description, rule_json) VALUES ('You can only use one word','{ "not": { "contains": " " } } ');
INSERT INTO post_rule (rule_description, rule_json) VALUES ('You cannot use vowel', '{ "vowels": false } ');
INSERT INTO post_rule (rule_description, rule_json) VALUES ('You cannot use the letter A','{ "not": { "contains": "a" } }');
INSERT INTO post_rule (rule_description, rule_json) VALUES ('You cannot use the letter E','{ "not": { "regex": "a" } }');
INSERT INTO post_rule (rule_description, rule_json) VALUES ('You cannot use the letter I','{ "not": { "regex": "i" } }');

-- 40
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (1,'true',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (2,'true',0, 2);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (3,'true',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (4,'true',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (5,'true',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (6,'true',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (7,'true',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (8,'true',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (9,'true',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (10,'false',0, 4);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (11,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (12,'false',0, 1);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (13,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (14,'false',0, 3);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (15,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (16,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (17,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (18,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (19,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (20,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (21,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (22,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (23,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (24,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (25,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (26,'false',0, 2);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (27,'false',0, 1);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (28,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (29,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (30,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (31,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (32,'false',0, 1);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (33,'false',0, 3);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (34,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (35,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (36,'false',0, 5);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (37,'false',0, 4);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (38,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (39,'false',0, NULL);
INSERT INTO "post" (post_id,private,comments, post_rule_id) VALUES (40,'false',0, NULL);

--10
INSERT INTO "comment" (comment_id,post_id) VALUES (41,26);
INSERT INTO "comment" (comment_id,post_id) VALUES (42,26);
INSERT INTO "comment" (comment_id,post_id) VALUES (43,32);
INSERT INTO "comment" (comment_id,post_id) VALUES (44,32);
INSERT INTO "comment" (comment_id,post_id) VALUES (45,33);
INSERT INTO "comment" (comment_id,post_id) VALUES (46,34);
INSERT INTO "comment" (comment_id,post_id) VALUES (47,35);
INSERT INTO "comment" (comment_id,post_id) VALUES (48,35);
INSERT INTO "comment" (comment_id,post_id) VALUES (49,37);
INSERT INTO "comment" (comment_id,post_id) VALUES (50,37);

--70 +
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (2,1,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (10,2,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (10,7,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (11,8,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (12,8,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (12,23,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (13,2,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (13,8,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (14,14,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (14,15,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (15,8,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (15,25,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (16,5,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (19,1,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (19,2,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (20,25,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (21,11,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (22,5,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (22,7,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (22,17,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (22,23,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (23,5,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (24,11,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (25,4,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (25,22,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (26,14,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (26,19,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (27,17,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (27,23,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (28,5,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (29,4,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (29,8,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (30,6,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (30,17,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (30,18,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (31,21,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (32,16,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (32,22,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (32,23,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (33,7,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (33,8,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (33,19,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (34,24,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (35,1,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (37,1,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (37,4,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (41,7,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (41,9,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (41,13,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (41,18,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (42,24,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (43,10,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (44,10,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (44,12,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (44,14,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (44,21,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (46,5,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (46,10,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (46,14,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (46,23,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (46,24,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (46,25,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (47,6,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (48,17,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (49,11,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (49,22,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (49,24,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (50,14,'false');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (50,19,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (50,21,'true');
INSERT INTO "appraisal" (content_id,user_id,"like") VALUES (1,4,'true');


--25
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (26,2,'2020-02-28T13:56:01-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (27,3,'2020-01-16T03:00:02-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (31,16,'2020-01-01T18:48:32-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (50,9,'2020-01-01T18:48:32-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (30,14,'2020-03-13T01:26:58-07:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (20,20,'2020-02-01T22:50:50-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (40,10,'2020-01-31T12:11:34-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (16,23,'2020-03-17T08:44:09-07:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (32,7,'2020-02-21T23:20:13-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (47,21,'2020-03-04T23:09:00-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (23,14,'2020-02-18T23:01:29-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (12,4,'2020-01-18T04:33:20-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (31,17,'2020-02-08T22:54:29-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (22,6,'2020-02-08T22:54:29-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (12,2,'2020-01-18T04:33:20-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (43,22,'2020-02-09T18:00:45-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (45,12,'2020-03-02T00:02:38-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (36,6,'2020-01-29T19:54:01-08:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (35,20,'2020-03-21T14:13:34-07:00');
INSERT INTO "content_report" (content_id,user_id,date_of_report) VALUES (29,1,'2020-02-20T02:27:29-08:00');


INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (3,'2020-04-07T04:11:14-07:00','Rinah Aguilar accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (9,'2021-03-26T11:43:50-07:00','Zephr Estes accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (11,'2019-07-27T23:18:40-07:00','Chillán Viejo accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (12,'2019-10-18T08:01:55-07:00','Chillán Viejo accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (13,'2020-05-27T11:38:04-07:00','Denise Stephenson accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (14,'2021-01-12T17:47:15-08:00','Drake Gallegos accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (15,'2020-08-23T10:23:06-07:00','Drake Gallegos accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (17,'2020-07-06T00:05:52-07:00','Rinah Aguilar accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (20,'2020-09-15T19:00:13-07:00','Rinah Aguilar accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (24,'2019-08-25T02:29:34-07:00','Evan Stout accepted your friend request');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (20,'2020-05-08T20:36:02-07:00','Rinah Aguilar commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (20,'2020-03-11T04:13:44-07:00','Claire Deleon commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (14,'2019-12-30T03:56:20-08:00','Reuben Stanton commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (14,'2019-07-07T12:14:36-07:00','Dennis Hartman commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (11,'2020-10-02T22:23:23-07:00','Elvis Randall commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (15,'2019-08-30T00:56:15-07:00','Dennis Hartman commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (10,'2020-05-21T16:13:38-07:00','Dale Diaz commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (10,'2019-09-07T16:38:22-07:00','Dennis Hartman commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (15,'2021-02-15T22:27:49-08:00','Broden Larsen commented on your post');
INSERT INTO "notification" (user_id,date_of_notification,description) VALUES (15,'2020-01-02T00:41:03-08:00','Mira Adams commented on your post');


INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(1,1);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(2,7);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(3,6);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(4,6);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(5,4);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(6,10);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(7,10);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(8,1);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(9,1);
INSERT INTO "notification_user" (notification_user_id,user_id) VALUES(10,3);

INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(11,26);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(12,26);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(13,32);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(14,32);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(15,33);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(16,34);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(17,35);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(18,35);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(19,37);
INSERT INTO "notification_content" (notification_content_id,content_id) VALUES(20,37);

INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (1,2,'2020-05-28T08:35:17-07:00','uwu :3','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (1,3,'2019-04-21T08:35:17-07:00','hello :3','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (1,6,'2020-03-20T00:37:22-07:00','wanna play fornite','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (1,8,'2019-07-15T01:21:45-07:00','do i know you','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (1,11,'2019-03-30T12:04:33-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (1,11,'2019-08-10T14:04:40-07:00','hey please answer me','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (1,11,'2020-03-20T06:42:26-07:00','hey, are you single?','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (2,4,'2019-12-11T15:37:19-08:00','do you sell homemade pies?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (2,4,'2020-02-08T16:24:33-08:00','not at all ','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (2,7,'2020-03-14T04:12:37-07:00','sup','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (2,9,'2020-03-14T08:11:03-07:00','hey you look nice','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (2,11,'2019-12-26T14:18:09-08:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (2,11,'2020-03-11T22:44:21-07:00','HEYYY','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (2,20,'2019-05-24T16:54:52-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (3,1,'2020-03-20T05:05:23-07:00','sup wanna get pizza','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (4,1,'2020-03-25T12:47:26-07:00','what rank are you on fortnite','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (4,20,'2019-11-29T04:47:14-08:00','Are you Jessica friend?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (5,3,'2020-03-29T23:05:06-07:00','x)','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (5,4,'2020-03-18T20:31:27-07:00','hey','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (5,12,'2019-06-12T16:54:22-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (5,12,'2020-01-29T19:58:10-08:00','whats ur favorite color? Mine is BLUEE','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (6,12,'2020-02-22T15:03:30-08:00','do you like mechanical keyboards? :3','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (6,22,'2020-03-27T00:36:27-07:00','sup','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (6,24,'2019-09-24T21:12:20-07:00','WOW','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (7,3,'2019-07-15T12:55:35-07:00','want to meet me at the library?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (7,9,'2019-09-18T07:29:23-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (7,9,'2019-12-14T12:29:14-08:00','wanna play guitar hero','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (7,12,'2020-03-28T17:34:04-07:00','do you still sell those cakes? :x','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (7,17,'2019-06-07T19:59:42-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (7,20,'2019-10-11T20:22:49-07:00','YOU ARE CANCELED!','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (7,23,'2019-05-11T14:53:04-07:00','hello :3','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,1,'2020-03-14T04:01:18-07:00','do you sell homemade pies?','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,1,'2020-03-22T10:18:48-07:00','Give me a break. Did you see what you just did? ','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,3,'2019-12-21T23:27:40-08:00','wanna play gta? 0.0','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,3,'2020-03-25T21:17:04-07:00','sup','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,10,'2019-11-05T22:12:47-08:00','Where did you get those jeans? They look amazing on you','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,10,'2019-12-08T06:04:16-08:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,11,'2019-03-31T07:55:09-07:00','Are you pauls father?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,11,'2019-11-22T08:41:27-08:00','I thought we were friends','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,23,'2019-05-28T18:08:34-07:00','Why did you take so long to accept my friend request?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (8,23,'2019-07-16T16:34:59-07:00','whats happening!?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (9,7,'2020-01-07T10:41:45-08:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (9,23,'2019-06-19T01:54:04-07:00','Are you Monicas friend?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (9,25,'2019-11-04T21:55:28-08:00','whaaat? that rumour about you is true?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (9,25,'2019-11-22T13:02:33-08:00','I cant belive u just did that ....','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (10,8,'2019-08-31T18:51:24-07:00','sup','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (10,14,'2020-01-04T20:47:09-08:00','I heard you have corona virus :(','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (11,1,'2019-07-29T10:39:46-07:00','Hey can you give me the test answers','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (11,2,'2019-09-10T04:52:34-07:00','you look just like you did in high school','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (11,8,'2020-03-15T01:20:27-07:00','hey','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (11,20,'2020-01-19T09:12:43-08:00','ok....','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (11,24,'2020-02-16T01:26:02-08:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (12,5,'2019-10-25T20:37:55-07:00','do you know how to hack someones account?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (12,5,'2020-03-02T21:17:11-08:00','do u know susan?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (12,5,'2020-03-26T08:59:50-07:00','sup','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (12,14,'2019-09-27T05:22:59-07:00',':)','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (12,24,'2019-05-24T22:39:23-07:00','xd','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (12,24,'2020-03-11T04:14:24-07:00','hey','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (13,3,'2020-03-25T21:11:38-07:00','wanna play smash bros?','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (13,15,'2020-01-29T23:50:05-08:00','do i know you?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (14,5,'2019-04-30T10:34:05-07:00','epic gamer moment right there','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (14,6,'2019-11-30T04:00:43-08:00','not at all','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (14,10,'2019-09-18T19:00:20-07:00','everyday I think about your homemade apple cider','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (14,10,'2019-09-24T16:24:55-07:00','do you sell you pizza?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (14,12,'2020-02-05T22:47:34-08:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (14,20,'2019-09-03T13:05:17-07:00','I do NOT consider you a bad person','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (14,20,'2019-11-10T19:05:19-08:00','sup','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (14,23,'2019-07-30T13:24:20-07:00','you are so random ahah','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (15,10,'2019-05-29T02:21:13-07:00','do you know how to use zoom','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (15,13,'2019-09-12T16:43:21-07:00','do u know mary?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (15,18,'2019-04-26T11:37:22-07:00','do you know how to hack someones account?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (15,19,'2019-03-18T23:19:43-07:00','hello :3','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (16,14,'2020-03-10T02:45:17-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (16,23,'2019-11-18T16:43:40-08:00','please!','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,3,'2020-03-29T16:07:45-07:00','how u doin?','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,9,'2019-03-01T17:17:45-08:00','to be honest i dont think your presentation was that bad','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,9,'2019-07-14T15:56:28-07:00','sup','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,9,'2020-03-11T04:25:38-07:00','you deserved it!','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,10,'2019-09-27T10:26:33-07:00','we need to work on that project real quick not gonna lie','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,10,'2020-03-20T15:42:01-07:00','dont worry about that :D','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,12,'2020-03-26T20:22:40-07:00','hey','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,16,'2019-06-25T10:53:31-07:00','hey, are you single?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,22,'2019-06-29T23:16:06-07:00','i dont know what to do','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,22,'2020-02-20T05:50:58-08:00','ay yo gurl lemme take a look at dem digits','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,22,'2020-03-24T18:24:52-07:00','do I know you from school?','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,22,'2020-03-25T04:55:57-07:00','hey','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (17,25,'2020-03-27T13:21:54-07:00','hey how much did that phone cost?','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (18,15,'2019-04-20T17:55:40-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (18,15,'2020-03-25T10:27:22-07:00','hey','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (18,25,'2019-09-18T19:57:21-07:00','where did you get ur dog? its so cute :3','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (19,12,'2020-03-12T23:06:23-07:00','ye i dont really like him either','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (19,15,'2019-03-14T22:05:29-07:00','do u know peter?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (19,20,'2019-04-13T00:55:16-07:00','sup','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (19,23,'2020-03-15T11:37:45-07:00','AAAAAAAAAAAAAAAA','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (19,23,'2020-03-20T22:19:57-07:00','DO NOT GHOST ME','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (20,2,'2020-03-12T04:15:49-07:00','sup','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (20,7,'2019-09-19T13:00:37-07:00','I do NOT give you permission to do "the plan"','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (20,11,'2019-05-10T16:54:33-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (20,11,'2020-01-03T19:31:51-08:00','hello can i speak with you real quick','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (20,14,'2020-01-20T12:39:31-08:00','hey?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (20,19,'2019-03-17T19:38:25-07:00','hi professor i wonder if you could help us in the project','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (20,22,'2019-04-19T19:52:30-07:00','sup','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (22,6,'2020-03-27T02:57:16-07:00','im sorry for everything ive ever done :( my mind was not in the right place','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (22,17,'2020-03-24T03:03:05-07:00','sup','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (22,20,'2019-09-15T20:00:06-07:00','Hello!','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (22,20,'2019-12-19T16:43:09-08:00','wanna play with me?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (22,25,'2019-07-20T12:28:53-07:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (23,7,'2019-12-30T11:15:16-08:00','whats ur opinion on the sharks?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (23,16,'2019-08-18T00:43:31-07:00','if you know me give me ur number','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (23,19,'2019-12-22T13:40:37-08:00','do u know john?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (23,24,'2019-05-13T12:12:56-07:00','this social network is very nice dont you agree','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (24,12,'2020-03-18T04:13:46-07:00','ye i agree','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (24,21,'2019-07-25T10:31:21-07:00','did you know mark belives in flat earth','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (24,23,'2020-03-18T06:47:22-07:00','do you think i am right about that situation?','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (25,2,'2020-03-14T17:38:58-07:00','sup','false');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (25,3,'2019-03-07T02:59:19-08:00','hey, are you single?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (25,4,'2019-10-12T01:10:03-07:00','look i want to talk to you about that thing','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (25,4,'2020-01-12T19:55:06-08:00','hey','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (25,6,'2019-08-20T03:00:43-07:00','hello :3','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (25,9,'2019-12-17T09:31:39-08:00','how u doin?','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (25,17,'2020-01-05T07:50:41-08:00','sup','true');
INSERT INTO "message" (sender_id,receiver_id,date_sent,content,seen) VALUES (25,18,'2019-06-16T01:33:18-07:00','hey','true');

INSERT INTO "admin" (admin_id) VALUES (1);

INSERT INTO "user_ban" (user_banned,banned_by,date_of_ban,reason_of_ban) VALUES (24,1,'2020-03-24T22:38:53-07:00','Spreading missinformation about covid-19');

INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2019-7-27T08:00:00-07:00',3600,'Server maintnence in 1 hour');
INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2019-8-28T08:00:00-07:00',3600,'Server maintnence in 1 hour');
INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2019-9-26T08:00:00-07:00',3600,'Server maintnence in 1 hour');
INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2019-12-16T08:00:00-07:00',3600,'Server maintnence in 1 hour');
INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2020-01-27T08:00:00-07:00',3600,'Server maintnence in 1 hour');
INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2020-02-15T05:48:31-08:00',604800,'Flatten the curve. Do not go oustide');
INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2020-02-16T08:00:00-08:00',3600,'Server maintnence in 1 hour');
INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2020-02-20T08:00:00-08:00',3600,'Server maintnence in 1 hour');
INSERT INTO "announcement" (author_id,date_of_creation,duration_secs,content) VALUES (1,'2020-02-15T05:48:31-08:00',8640000,'Users who post misinformation about covid-19 will be BANNED');

REFRESH MATERIALIZED VIEW post_comments_view;
