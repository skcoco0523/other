<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Artist;
use App\Models\Album;
use App\Models\Music;
use App\Models\Affiliate;
//use App\Models\FileTmp;

//アルバムコントローラー
class AdminAlbumController extends Controller
{
    //追加
    public function album_regist(Request $request)
    {
        $input['admin_flag']            = true;
        $input['art_name_asc']          = true;
        $artists = Artist::getArtist_list(null,false,null,$input);  //全件　リスト用
        $input['art_name_asc']          = false;

        $input['cdate_desc']            = true;
        $albums = Album::getAlbum_list(5,false,null,$input);  //5件
        $msg = request('msg');
        
        //追加からのリダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        return view('admin.admin_home', compact('artists', 'albums', 'input', 'msg'));
    }
    //追加
    public function album_reg(Request $request)
    {
        $input = $request->all();
        $msg=null;
        //Album,Affiliate,Musicを一括で登録するため、事前にデータ確認
        if(!$input['no_link']){
            if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        }
        if(!$input['art_id'])       $msg =  "登録されていないアーティストは選択できません。";
        if(!$input['art_name'])     $msg =  "アーティストを選択してください。";
        if(!$input['alb_name'])     $msg =  "アルバム名を入力してください。";
        if($msg!==null)         return redirect()->route('admin-album-reg', ['input' => $input, 'msg' => $msg]);
        //dd($data);
        
        if($input['no_link']){
            $aff_id=null;
        }else{
            //affiliate登録
            $ret = Affiliate::createAffiliate($input);
            if($ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
            if($ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
            if($ret['error_code']==-1)    $msg = "アフィリエイト情報の登録に失敗しました。";
            if($msg!==null) return redirect()->route('admin-album-reg', ['input' => $input, 'msg' => $msg]);
            $aff_id=$ret['id']; //追加したAffiliateID
        }
        //Album登録
        $input = $request->only(['alb_name', 'art_id','release_date']);
        $input['name'] = $input['alb_name'];    //albumのカラム名に合わせる
        $input['aff_id'] = $aff_id;          //AffiliateID追加

        $ret = Album::createAlbum($input);      //二重のエラー判定だが念のため
        if($ret['error_code']==1)     $msg = "アルバム名を入力してください。";
        if($ret['error_code']==2)     $msg = "アーティストを選択してください。";
        if($ret['error_code']==3)     $msg = "アフィリエイト情報の登録に失敗しました。";
        if($ret['error_code']==-1)    $msg = "アルバム情報の登録に失敗しました。";
        if($msg!==null) return redirect()->route('admin-album-reg', ['input' => $input, 'msg' => $msg]);
        $alb_id=$ret['id']; //追加したAlbimID

        //Music登録　必須ではないためデータがない場合はここでリダイレクト
        // 改行で分割して配列に格納     空の要素を削除して配列を整理
        //$input = $request->only(['art_id', 'release_date']);  //アルバムにリリース日情報があるため不要
        $input = $request->only(['art_id']);
        $input['alb_id'] = $alb_id;          //AlbumID追加

        // 入力文字列をUTF-8として扱い、改行で分割
        //$add_mus_list = array_filter(array_map('trim', preg_split('/\R/', $request->input('music_list'))));
        $add_mus_list = array_filter(array_map('trim', preg_split('/\R/u', $request->input('music_list'))));
        foreach ($add_mus_list as $music) {
            if($music !== ""){
                $input['name'] = $music;    //musicsの追加カラム
                $ret = Music::createMusic($input);
            }
        }


        //直近で登録したアルバム取得
        $msg = "アルバム：" . $request->input('alb_name') . " を追加しました。";
        return redirect()->route('admin-album-reg', ['input' => $input, 'msg' => $msg]);
    }
    //検索
    public function album_search(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        
        $input['admin_flag']            = true;
        $input['search_artist']         = get_proc_data($input,"search_artist");
        $input['search_album']          = get_proc_data($input,"search_album");

        $input['page']                  = get_proc_data($input,"page");

        $input['alb_name_asc']          = true;
        $album = Album::getAlbum_list(10,true,$input['page'],$input);  //件数,ﾍﾟｰｼﾞｬｰ,ｶﾚﾝﾄﾍﾟｰｼﾞ,ｷｰﾜｰﾄﾞ
        $input['alb_name_asc']          = false;

        $input['art_name_asc']          = true;
        $artist = Artist::getArtist_list();  //全件　リスト用
        $msg = request('msg');
        
        return view('admin.admin_home', compact('artist', 'album', 'input', 'msg'));
    }
    //削除
    public function album_del(Request $request)
    {
        $data = $request->only(['id']);
        $msg = Album::delAlbum($data['id']);
        $input = $request->all();
        return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
    }
    //変更
    public function album_chg(Request $request)
    {
        //$input = $request->only(['id', 'alb_name', 'art_id', 'art_name', 'release_date', 'aff_id', 'aff_link', 'keyword']);
        $input = $request->all();
        $msg=null;
        //Album,Affiliate,Musicを一括で登録するため、事前にデータ確認
        //if(!$input['aff_link'])     $msg =  "アフィリエイトリンクを入力してください。";
        if(!$input['art_id'])       $msg =  "登録されていないアーティストは選択できません。";
        if(!$input['art_name'])     $msg =  "アーティストを選択してください。";
        if(!$input['alb_name'])     $msg =  "アルバム名を入力してください。";
        if(!$input['id'])           $msg =  "テーブルから選択してください。";
        if($msg!==null)         return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);

        //affiliate変更
        //$input = $request->only(['aff_link', 'aff_id']);

        if($input['aff_link']){
            //既存aff_idがあれば更新　なければ作成後にアルバム情報を更新
            if($input['aff_id']){
                $ret = Affiliate::chgAffiliate($input);
            }else{
                $ret = Affiliate::createAffiliate($input);
                $input['aff_id']=$ret['id']; //追加したAffiliateID
            }

            if($ret['error_code']==1)     $msg = "アフィリエイトリンクを入力してください。";
            if($ret['error_code']==2)     $msg = "アフィリエイトリンクが不正です。(URLと画像情報が必要)";
            if($ret['error_code']==-1)    $msg = "アフィリエイト情報の登録に失敗しました。";
            if($msg!==null) return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
        }
        
        //Album変更
        //$input = $request->only(['id', 'alb_name', 'art_id','release_date']);
        $input['name'] = $input['alb_name'];    //albumのカラム名に合わせる
        if($input){
            $ret = Album::chgAlbum($input);
            //最初にデータ不足の判定済み
            if($ret['error_code']==-1)    $msg = "アルバム情報の更新に失敗しました。";
            if($msg!==null) return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
            $alb_id=$ret['id']; //変更したAlbimID
        
        }
        //収録曲は詳細変更でのみ可能
        //$input = $request->only(['keyword']);
        $msg = "アルバム：" . $request->input('alb_name') . " を更新しました。";
        return redirect()->route('admin-album-search', ['input' => $input, 'msg' => $msg]);
    }
    //詳細変更
    public function album_chg_detail(Request $request)
    {
        //リダイレクトの場合、inputを取得
        if($request->input('input')!==null)     $input = request('input');
        else                                    $input = $request->all();
        if (empty($input['keyword']))           $input['keyword']=null;
        if (empty($input['id']))            $input['id']=null;

        //収録曲変更
        $chg_flg = 1;
        $album_detail = Album::getAlbum_detail($input['id']);  //全件,なし,ｷｰﾜｰﾄﾞ　リスト用
        $album = null;
        
        $msg = request('msg');
        $msg = ($msg===NULL && $input['keyword'] !==null && $albums === null) ? "検索結果が0件です。" : $msg;

        
        return view('admin.admin_home', compact('album_detail', 'album', 'input', 'msg'));
    }
    //詳細変更用　関数(変更・削除・追加)
    public function album_chg_detail_fnc(Request $request)
    {
        make_error_log("album_chg_detail_fnc.log","-----start-----");
        //$input = $request->only(['fnc', 'alb_id', 'mus_id','name']);
        $input = $request->all();
        $msg=null;
        make_error_log("album_chg_detail_fnc.log","fnc=".$input['fnc']." alb_id=".$input['alb_id']." mus_id=".$input['mus_id']." name=".$input['name']);
        $input['id']=$input['mus_id'];
        switch($input['fnc']){
            case "chg":
                $ret = Music::chgMusic($input);
                if($ret['error_code']==-1)    $msg = "収録曲の更新に失敗しました。";
                if($ret['error_code']==0)    $msg = "収録曲を更新しました。";
                break;

            case "del":
                $ret = Music::delMusic($input['id']);
                if($ret['error_code']==-1)    $msg = "収録曲の削除に失敗しました。";
                if($ret['error_code']==0)    $msg = "収録曲を削除しました。";
                break;

            case "add":
                if($input['name'] == null){
                    $msg = "アルバムに追加する楽曲名を入力してください。";
                }else{
                    //album情報を取得　既存art_idを付与
                    $album = Album::getalbum_detail($input['alb_id']);
                    //album情報から既存art_idを取得
                    $input['art_id'] = $album->art_id;
                    $ret = Music::createMusic($input);
                    if($ret['error_code']==-1)    $msg = "収録曲の追加に失敗しました。";
                    if($ret['error_code']==0)    $msg = "収録曲を追加しました。";
                }
                break;

            default:
        }
        $input['id'] = $input['alb_id'];
        return redirect()->route('admin-album-chgdetail', ['input' => $input, 'msg' => $msg]);
    }
}
