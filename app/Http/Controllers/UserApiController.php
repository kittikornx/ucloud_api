<?php

namespace App\Http\Controllers;

use App\Member;
use App\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserApiController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
  
        $user = Member::query()
                ->where('user', '=', trim($username))
                ->where('password', '=', $password)
                ->first();
        
        $token = UserToken::generateToken();

        if (!$user) {
            return response()->json(['success' => false , 'message' => 'กรุณาตรวจสอบชื่อหรือรหัสผ่านอีกครั้ง'] , 404);
        }
        
        if ($user->token == '') {
            UserToken::query()
                ->where('m_id', $user->m_id)
                ->update([
                    'token' => $token,
                    'created_at' => date('Y-m-d H:i:s')
                ]);   
        } else {
            UserToken::query()
                ->where('m_id', $user->m_id)
                ->update([
                    'token' => $token,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        $data =  UserToken::query()
            ->where('m_id', $user->m_id)
            ->first();
        
        return response()->json(['success' => true, 'token' =>$data->token ] , 401);
        
    }

    public function info(Member $member)
    {
        $o_id = $member->o_id;
        $owner = DB::table('owner AS ow')
                    ->leftJoin('title_name AS tn', 'ow.tn_id', '=', 'tn.tn_id')
                    ->leftJoin('owner_add AS oa', 'ow.o_id', '=', 'oa.o_id')
                    ->leftJoin('address AS a', 'oa.a_id', '=', 'a.a_id')
                    ->select('ow.o_name','ow.tn_id','ow.o_code','ow.o_status','tn.tn_detail','a.a_full')
                    ->where('ow.o_id',$o_id)
                    ->first();

        if ($owner->o_status == 0) {
            $owner->tn_detail = $owner->tn_detail;
        } else {
            switch ($owner->tn_id) {
                case '1':
                    $owner->tn_detail = 'บริษัท ';
                break;
                case '2':
                    $owner->tn_detail = 'ห้างหุ้นส่วน';
                break;
                case '3':
                    $owner->tn_detail = 'รัฐวิสาหกิจ/องค์กรของรัฐ';
                break;
                case '4':
                    $owner->tn_detail = 'ส่วนราชการ';
                break;
                case '5':
                    $owner->tn_detail = 'วัด/ศาสนา';
                break;
                case '6':
                    $owner->tn_detail = 'สมาคม';
                break;
                case '7':
                    $owner->tn_detail = 'มูลนิธิ';
                break;
                case '8':
                    $owner->tn_detail = 'สหกรณ์';
                break;
                case '9':
                    $owner->tn_detail = 'ธนาคาร';
                break;
                case '10':
                    $owner->tn_detail = 'นิติบุคคลอื่นๆ';
                break;
                default:
                    $owner->tn_detail = '';
                break;
            }
        }

        $land = DB::table('owner_land AS ol')
                    ->join('land AS l' , 'ol.l_id' , '=', 'l.l_id')
                    ->where('ol.o_id', $o_id)
                    ->get();

        $building = DB::table('owner_building AS ob')
                    ->join('building AS b' , 'ob.bu_id' , '=' , 'b.bu_id')
                    ->where('ob.o_id', $o_id)
                    ->get();

        return response()->json([
            'success' => true ,
            'owner' => $owner ,
            'land'  => $land ,
            'building'  => $building
        ], 401);
    }

    public function logout(Request $request, Member $member)
    {
        $token = $request->get('token');
   
        $user = Member::query()
                    ->where('m_id', $member->m_id)
                    ->update(['token' => '']);
        
        return response()->json(['success' => true, 'token' => $token]);
    }

    public function resetPassword(Request $request, Member $member)
    {   
        return response()->json(['success' => true]);
    }

}
