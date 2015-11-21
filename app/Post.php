<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class Post extends Model {
	protected $table = 'posts';
	protected $fillable
		= [
			'title',
			'content',
			'author',
			'group',
			'isIncognito',
			'base',
			'type',
		];

	public static function getPostInfoById( $id ) {
		$posts = Post::all()->where( 'id', intval( $id ) );
		if ( $posts->count() == 0 ) {
			return null;
		}

		$post           = $posts->first();
		$p              = new stdClass();
		$p->id          = $post->id;
		$p->title       = $post->title;
		$p->content     = $post->content;
		$p->group       = $post->group;
		$p->author      = User::getInfoById( $post->author );
		$p->isIncognito = $post->isIncognito;
		$p->type        = $post->type;
		$p->base        = $post->base;
		$p->comments    = Comment::getCommentsByPostId( $post->id );

		return $p;
	}
}
