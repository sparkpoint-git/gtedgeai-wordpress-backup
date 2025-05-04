<?php
namespace FileBird\Model;

use FileBird\Classes\Helpers;
use FileBird\Model\SettingModel;

defined( 'ABSPATH' ) || exit;

class Folder {
	private static $folder_table   = 'fbv';
	private static $relation_table = 'fbv_attachment_folder';

	const ALL_CATEGORIES  = -1;
    const UN_CATEGORIZED  = 0;
	const PREVIOUS_FOLDER = -2;

	public static function allFolders( $select = '*', $order_by = null, $order = null, $search = null, $output = OBJECT ) {
		//TODO need to convert ord to number using +0
		global $wpdb;

		$check_author = apply_filters( 'fbv_will_check_author', true );
		$conditions = array(
			'1 = 1'
		);
		if( $check_author ) {
			$conditions[] = 'created_by = ' . apply_filters( 'fbv_folder_created_by', 0 );
		}
		if ( ! empty( $search ) ) {
			$conditions[] = "name LIKE '%" . $wpdb->esc_like( $search ) . "%'";
		}
		$conditions = implode( ' AND ', $conditions );
		$sql        = "SELECT $select FROM " . self::getTable( self::$folder_table ) . ' WHERE ' . $conditions . ' ORDER BY `ord` ASC';

		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$folders = $wpdb->get_results( $sql, $output );

		if ( 'name' === $order_by && in_array( $order, array( 'asc', 'desc' ), true ) ) {
			usort( $folders, array( __CLASS__, "sort_natural_$order" ) );
		}

		return $folders;
	}

	private static function sort_natural_asc( $a, $b ) {
		return strnatcasecmp( $a->name, $b->name );
	}

	private static function sort_natural_desc( $a, $b ) {
		return strnatcasecmp( $a->name, $b->name ) * -1;
	}

	public static function countFolder() {
		global $wpdb;
		return intval( $wpdb->get_var( 'SELECT count(*) as c FROM ' . self::getTable( self::$folder_table ) ) );
	}

	public static function updateOrdAndParent( $id, $new_ord, $new_parent ) {
		global $wpdb;
		$wpdb->update(
			self::getTable( self::$folder_table ),
			array(
				'parent' => $new_parent,
				'ord'    => $new_ord,
			),
			array( 'id' => $id ),
			array( '%d', '%d' ),
			array( '%d' )
		);
	}
	public static function verifyAuthor( $folder_id, $current_user_id, $folder_per_user = false ) {
		global $wpdb;
		if( $folder_id == 0 ) {
			return true;
		}
		$created_by = (int) $wpdb->get_var( $wpdb->prepare( "SELECT `created_by` FROM {$wpdb->prefix}fbv WHERE `id` = %d", $folder_id ) );
		if( $folder_per_user ){
			return $created_by == $current_user_id;
		} else {
			return $created_by == 0;
		}
	}
	public static function updateAuthor( $from_author, $to_author ) {
		global $wpdb;
		$wpdb->update(
			self::getTable( self::$folder_table ),
			array(
				'created_by' => $to_author,
			),
			array( 'created_by' => $from_author ),
			array( '%d' ),
			array( '%d' )
		);
	}
	public static function deleteByAuthor( $author ) {
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->prefix}fbv_attachment_folder WHERE folder_id IN (SELECT id FROM {$wpdb->prefix}fbv WHERE created_by = " . (int) $author . ')' );
		$wpdb->query( "DELETE FROM {$wpdb->prefix}fbv WHERE created_by = " . (int) $author );
	}

	public static function rawInsert( $query ) {
		global $wpdb;
		$wpdb->query( 'INSERT INTO ' . self::getTable( self::$folder_table ) . ' ' . $query );
	}

	public static function getFolderIdFromPostId( $post_id ) {
		global $wpdb;
		return $wpdb->get_var( 'SELECT `folder_id` FROM ' . self::getTable( self::$relation_table ) . ' WHERE `attachment_id` = ' . (int) $post_id . ' GROUP BY `folder_id`' );
	}

	public static function getFolderFromPostId( $post_id ) {
		global $wpdb;

		$created             = 0;
		$user_has_own_folder = SettingModel::getInstance()->get( 'user_mode' ) === '1';
		if ( $user_has_own_folder ) {
			$created = get_current_user_id();
		}
		return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT `folder_id`,`name` FROM {$wpdb->prefix}fbv as fbv
				JOIN {$wpdb->prefix}fbv_attachment_folder as fbva ON fbv.id = fbva.folder_id
				WHERE `attachment_id` = %d AND `created_by` = %d GROUP BY `folder_id`",
			$post_id,
                $created
			),
            OBJECT
            );
	}

	private static function getNestedFolder() {
		global $wpdb;

		$counterType = SettingModel::getInstance()->get( 'folder_counter_type' );
		$isUsed      = apply_filters( 'fbv_counter_type', $counterType ) === 'counter_file_in_folder_and_sub';

		if ( ! $isUsed ) {
			return array();
		}
		$check_author = apply_filters( 'fbv_will_check_author', true );
		if( $check_author ) {
			$query = $wpdb->prepare(
				"SELECT parent,GROUP_CONCAT(id) as child 
				FROM {$wpdb->prefix}fbv
				WHERE created_by = %d
				GROUP BY parent",
				apply_filters( 'fbv_folder_created_by', 0 )
			);
		} else {
			$query = "SELECT parent,GROUP_CONCAT(id) as child 
				FROM {$wpdb->prefix}fbv
				GROUP BY parent";
		}

		$result       = $wpdb->get_results( $query );
		$nestedFolder = array();

		foreach ( $result as $v ) {
			if ( $v->parent !== $v->child ) {
				$nestedFolder[ $v->parent ] = explode( ',', $v->child );
			}
		}

		return $nestedFolder;
	}

	private static function getNestedCountAttachments( $counters, $nestedFolder, $folder_id ) {
		$c = 0;

		if ( isset( $counters[ $folder_id ] ) ) {
			$c = intval( $counters[ $folder_id ]->counter );
		}

		if ( ! isset( $nestedFolder[ $folder_id ] ) ) {
			return $c;
		}

		$total = $c;

		foreach ( $nestedFolder[ $folder_id ] as $folder_id => $children_id ) {
			$total += self::getNestedCountAttachments( $counters, $nestedFolder, $children_id );
		}

		return $total;
	}

	public static function countAttachments( $lang = null ) {
        global $wpdb;
		$check_author = apply_filters( 'fbv_will_check_author', true );
        if( $check_author ) {
			$query = $wpdb->prepare(
				"SELECT folder_id, count(attachment_id) as counter
					FROM {$wpdb->prefix}posts AS `posts`
					INNER JOIN {$wpdb->prefix}fbv_attachment_folder AS `fbva` ON (fbva.attachment_id = posts.ID AND posts.post_type = 'attachment')
					INNER JOIN {$wpdb->prefix}fbv AS `fbv` ON (fbva.folder_id = fbv.id AND fbv.created_by = %d)
					WHERE posts.post_status != 'trash'
					GROUP BY folder_id",
					apply_filters( 'fbv_folder_created_by', 0 )
				);
		} else {
			$query = "SELECT folder_id, count(attachment_id) as counter
					FROM {$wpdb->prefix}posts AS `posts`
					INNER JOIN {$wpdb->prefix}fbv_attachment_folder AS `fbva` ON (fbva.attachment_id = posts.ID AND posts.post_type = 'attachment')
					INNER JOIN {$wpdb->prefix}fbv AS `fbv` ON (fbva.folder_id = fbv.id)
					WHERE posts.post_status != 'trash'
					GROUP BY folder_id";
		}

		$nestedFolder = self::getNestedFolder();
		$query        = apply_filters( 'fbv_all_folders_and_count', $query, $lang );

        $counters         = $wpdb->get_results( $query, OBJECT_K );
        $formattedCounter = array();
		$actualCounter    = array();

		foreach ( $nestedFolder as $folder_id => $counter ) {
		    $formattedCounter[ $folder_id ] = self::getNestedCountAttachments( $counters, $nestedFolder, $folder_id );
		}

        foreach ( $counters as $counter ) {
			$actualCounter[ $counter->folder_id ] = $counter->counter;
        }

		return array(
			'display' => array_replace( $actualCounter, $formattedCounter ),
			'actual'  => $actualCounter,
		);
    }

	public static function assignFolder( int $folderId, array $attachmentIds, string $lang ) {
        global $wpdb;

        $ids = implode( ',', $attachmentIds );

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $cleanQuery = $wpdb->prepare(
            "DELETE `fbva` FROM {$wpdb->prefix}fbv_attachment_folder AS `fbva`
            INNER JOIN {$wpdb->prefix}fbv AS `fbv`
            ON (fbva.folder_id = fbv.id AND fbv.created_by = %d)
            WHERE attachment_id IN ({$ids})",
            apply_filters( 'fbv_folder_created_by', 0 )
        );

        $wpdb->query( $cleanQuery );

        if ( $folderId > 0 ) {
            $prepareInsert = '';

            foreach ( $attachmentIds as $attachmentId ) {
                $prepareInsert .= "($folderId, $attachmentId),";
            }

            $prepareInsert = rtrim( $prepareInsert, ',' );

            $insertQuery = "INSERT INTO {$wpdb->prefix}fbv_attachment_folder ( folder_id, attachment_id ) VALUES {$prepareInsert}";

            $wpdb->query( $insertQuery );
        }
		do_action( 'fbv_after_assign_folder', $folderId, $attachmentIds );
        // Clean cache in wordpress.com
        if ( count( $attachmentIds ) > 0 ) {
            clean_post_cache( $attachmentIds[0] );
        }

        return self::countAttachments( $lang );
    }

	public static function setFoldersForPosts( $post_ids, $folder_ids, $has_action = true ) {
		global $wpdb;
		if ( ! is_array( $post_ids ) ) {
			$post_ids = array( $post_ids );
		}
		if ( ! is_array( $folder_ids ) ) {
			$folder_ids = array( $folder_ids );
		}
		$user_has_own_folder = SettingModel::getInstance()->get( 'user_mode' ) === '1';
		$current_user_id     = get_current_user_id();

		foreach ( $folder_ids as $k => $folder_id ) {
			$folder_id = (int) $folder_id;
			foreach ( $post_ids as $k2 => $post_id ) {
				do_action( 'fbv_before_setting_folder', (int) $post_id, $folder_id );
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fbv_attachment_folder WHERE `attachment_id` = %d AND `folder_id` IN (SELECT `id` FROM {$wpdb->prefix}fbv WHERE `created_by` = %d)", (int) $post_id, $user_has_own_folder ? $current_user_id : 0 ) );
				if ( $folder_id > 0 ) {
					$wpdb->insert(
						self::getTable( self::$relation_table ),
						array(
							'attachment_id' => (int) $post_id,
							'folder_id'     => $folder_id,
						),
						array( '%d', '%d' )
					);
				}
				if ( $has_action === true ) {
					do_action( 'fbv_after_set_folder', $post_id, $folder_id );
				}
			}
		}
		if ( count( $post_ids ) > 0 ) {
			clean_post_cache( $post_ids[0] );
		}
	}
	public static function detail( $name, $parent ) {
		global $wpdb;
		$name                = wp_kses_post( $name );
		$user_has_own_folder = SettingModel::getInstance()->get( 'user_mode' ) === '1';
		if ( $user_has_own_folder ) {
			$created = get_current_user_id();
		} else {
			$created = 0;
		}
		return $wpdb->get_row(
            $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}fbv WHERE `name` = %s AND `parent` = %d AND `created_by` = %d",
            $name,
            $parent,
            $created
            )
		);
	}
	public static function findById( $folder_id, $select = 'id' ) {
		global $wpdb;
		$query = 'SELECT ' . $select . ' FROM ' . self::getTable( self::$folder_table ) . " WHERE `id` = '" . (int) $folder_id . "'";
		return $wpdb->get_row( $query );
	}

	public static function isFolderExist( $folder_id ) {
		return self::findById( $folder_id ) !== null;
	}

	public static function updateFolderName( $new_name, $parent, $folder_id ) {
		global $wpdb;
		$new_name   = sanitize_text_field( wp_unslash( wp_kses_post( $new_name ) ) );
		$new_name   = Helpers::sanitize_for_excel( $new_name );
		$exist_name = $wpdb->get_row(
            $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}fbv WHERE id != %d AND name = %s AND parent = %d",
                $folder_id,
                $new_name,
                $parent
            )
        );

		if ( \is_null( $exist_name ) ) {
            $wpdb->update(
				self::getTable( self::$folder_table ),
				array( 'name' => $new_name ),
				array( 'id' => $folder_id ),
				array( '%s' ),
				array( '%d' )
			);
			do_action( 'fbv_after_folder_renamed', $folder_id, $new_name );
			return true;
		}

		return false;
	}
	public static function updateParent( $folder_id, $new_parent ) {
		global $wpdb;
		$wpdb->update(
			self::getTable( self::$folder_table ),
			array( 'parent' => $new_parent ),
			array( 'id' => $folder_id ),
			array( '%d' ),
			array( '%d' )
		);
		do_action( 'fbv_after_parent_updated', $folder_id, $new_parent );
	}
	public static function deleteAll() {
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->prefix}fbv" );
		$wpdb->query( "DELETE FROM {$wpdb->prefix}fbv_attachment_folder" );
		do_action( 'fbv_after_delete_all' );
	}
	public static function deleteFolderAndItsChildren( $id ) {
		global $wpdb;
		$wpdb->delete( self::getTable( self::$folder_table ), array( 'id' => $id ), array( '%d' ) );
		$wpdb->delete( self::getTable( self::$relation_table ), array( 'folder_id' => $id ), array( '%d' ) );
		$folder_colors = get_option( 'fbv_folder_colors', array() );

		if ( ! empty( $folder_colors ) && isset( $folder_colors[ $id ] ) ) {
			unset( $folder_colors[ $id ] );
			update_option( 'fbv_folder_colors', $folder_colors );
		}

		//delete it's children
		$children = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}fbv WHERE parent = %d", $id ) );
		foreach ( $children as $k => $child ) {
			self::deleteFolderAndItsChildren( $child );
		}
	}

	public static function newFolder( $name, $parent = 0 ) {
		global $wpdb;

		$ord = $wpdb->get_var( $wpdb->prepare( "SELECT MAX(ord) FROM {$wpdb->prefix}fbv WHERE parent = %d AND created_by = %d", $parent, apply_filters( 'fbv_folder_created_by', 0 ) ) );

		$name = sanitize_text_field( wp_kses_post( Helpers::sanitize_for_excel( $name ) ) );

		$data = array(
			'name'       => $name,
			'parent'     => $parent,
			'type'       => 0,
			'created_by' => apply_filters( 'fbv_folder_created_by', 0 ),
			'ord'        => is_null( $ord ) ? 0 : ( intval( $ord ) + 1 ),
		);

		$inserted = $wpdb->insert( self::getTable( self::$folder_table ), $data );

		if ( $inserted ) {
            $insertId = $wpdb->insert_id;

            $data = array(
                'title'      => $name,
                'id'         => $insertId,
                'key'        => $insertId,
                'type'       => 0,
                'parent'     => $parent,
                'children'   => array(),
                'data-count' => 0,
                'data-id'    => $insertId,
            );
			do_action( 'fbv_after_folder_created', $insertId, $data );
			return $data;
        }

        return false;
	}

	public static function newOrGet( $name, $parent, $return_id_if_exist = true ) {
		$check = self::detail( $name, $parent );
		if ( is_null( $check ) ) {
			return self::newFolder( $name, $parent );
		} else {
			return $return_id_if_exist ? array( 'id' => (int) $check->id ) : false;
		}
	}

	public static function deleteFoldersOfPost( $post_id ) {
		global $wpdb;
		$wpdb->delete(
			self::getTable( self::$relation_table ),
			array( 'attachment_id' => $post_id ),
			array( '%d' )
		);
	}

	public static function getChildrenOfFolder( $folder_id, $index = 0 ) {
		global $wpdb;
		$detail = null;
		if ( $index == 0 ) {
			$detail = $wpdb->get_results( 'SELECT name, id FROM ' . $wpdb->prefix . 'fbv WHERE id = ' . (int) $folder_id );
		}
		$children = $wpdb->get_results( 'SELECT name, id FROM ' . $wpdb->prefix . 'fbv WHERE parent = ' . (int) $folder_id );
		foreach ( $children as $k => $v ) {
			$children[ $k ]->children = self::getChildrenOfFolder( $v->id, $index + 1 );
		}
		if ( $detail != null ) {
			$return           = new \stdClass();
			$return->id       = $detail[0]->id;
			$return->name     = $detail[0]->name;
			$return->children = $children;

			return $return;
		}
		return $children;
	}

	private static function getTable( $table ) {
		global $wpdb;
		return $wpdb->prefix . $table;
	}

	public static function getRelationsWithFolderUser( $clauses ) {
		global $wpdb;

		$attachment_in_folder = $wpdb->prepare(
			"SELECT attachment_id 
			FROM {$wpdb->prefix}fbv_attachment_folder AS fbva
			JOIN {$wpdb->prefix}fbv AS fbv ON fbva.folder_id = fbv.id
			GROUP BY attachment_id
			HAVING FIND_IN_SET(%d, GROUP_CONCAT(created_by))",
			apply_filters( 'fbv_folder_created_by', 0 )
		);

		$clauses['where'] .= " AND {$wpdb->posts}.ID NOT IN ($attachment_in_folder) ";

		return $clauses;
	}

	public static function delete( array $ids, $lang ) {
        $folderColors = get_option( 'fbv_folder_colors', array() );

        self::_delete( $ids, $folderColors );
        update_option( 'fbv_folder_colors', $folderColors );

        return self::countAttachments( $lang );
    }

	public static function getChildrenIds( int $id ) {
        global $wpdb;

		return $wpdb->get_col( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}fbv WHERE parent = %d", $id ) );
    }

	public static function _delete( array $ids, array $folderColors ) {
        global $wpdb;

        foreach ( $ids as $id ) {
			if ( self::isFolderExist( $id ) && apply_filters( 'fbv_can_delete_folder', true, $id ) ) {
				$wpdb->delete( $wpdb->prefix . 'fbv', array( 'id' => $id ), array( '%d' ) );
            	$wpdb->delete( $wpdb->prefix . 'fbv_attachment_folder', array( 'folder_id' => $id ), array( '%d' ) );

				do_action( 'fbv_after_folder_deleted', $id );

				if ( ! empty( $folderColors ) && isset( $folderColors[ $id ] ) ) {
					unset( $folderColors[ $id ] );
				}

				$childrenIds = self::getChildrenIds( $id );
				self::_delete( $childrenIds, $folderColors );
			}
		}
    }
	public static function exportAll() {
		global $wpdb;

		$folders       = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fbv", ARRAY_A );
		$attachmentIds = $wpdb->get_results( "SELECT folder_id, GROUP_CONCAT(attachment_id SEPARATOR '|') as attachment_ids FROM {$wpdb->prefix}fbv_attachment_folder GROUP BY (folder_id)", OBJECT_K );
		foreach ( $folders as $key => $folder ) {
			$folder['name'] = '"' . Helpers::sanitize_for_excel( $folder['name'] ) . '"';
			if ( isset( $folder ) && isset( $attachmentIds[ $folder['id'] ] ) ) {
				$folder['attachment_ids'] = $attachmentIds[ $folder['id'] ]->attachment_ids ? $attachmentIds[ $folder['id'] ]->attachment_ids : '';
			} else {
				$folder['attachment_ids'] = '';
			}
			$folder['post_type'] = 'attachment';
			$folders[$key] = $folder;
		}

		//get all terms of taxonomies
		$enabledPostType = get_option( 'fbv_enabled_posttype', '' );
		$enabledPostType = explode( ',', $enabledPostType );
		foreach ( $enabledPostType as $postType ) {
			$terms = get_terms( array(
				'taxonomy'   => 'fbv_pt_tax_' . $postType,
				'hide_empty' => false,
				'meta_key'   => 'fbv_tax_order',
				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
			) );
			foreach ( $terms as $term ) {
				$folder = array();
				
				$posts = get_posts( array(
					'fields' => 'ids',
					'post_type' => $postType,
					'numberposts' => -1,
					'tax_query' => array(
						array(
							'taxonomy' => 'fbv_pt_tax_' . $postType,
							'field' => 'term_id',
							'terms' => $term->term_id,
						),
					),
				) );
				$attachment_ids = is_array( $posts ) ? implode( '|', $posts ) : '';

				$folder['id'] = $term->term_id;
				$folder['name'] = '"' . Helpers::sanitize_for_excel( $term->name ) . '"';
				$folder['parent'] = $term->parent;
				$folder['type'] = 0;
				$folder['ord'] = get_term_meta( $term->term_id, 'fbv_tax_order', true );
				$folder['created_by'] = get_term_meta( $term->term_id, 'fbv_author', true );
				$folder['attachment_ids'] = $attachment_ids;
				$folder['post_type'] = $postType;
				
				$folders[] = $folder;
			}
		}
		return $folders;
	}
}