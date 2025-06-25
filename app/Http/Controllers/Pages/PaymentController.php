<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payment;
use App\Models\Candidate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    function vnpay_payment(Request $request) {
        $data = $request->all();

        $vnp_TmnCode = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');

        $vnp_TxnRef = date('YmdHis');
        $vnp_OrderInfo = session('user.id')."-".$data['type']."-".$vnp_TxnRef;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $data['amount'] * 100; // nhân 100 vì VNPAY yêu cầu đơn vị là VND * 100
        $vnp_Locale = "vn";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) $hashdata .= '&';
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
            $i = 1;
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // TODO: Nếu muốn lưu đơn hàng vào bảng table_abc(), hãy thực hiện ở đây
        Payment::create([
            'id_user' => session('user.id'),
            'txn_ref' => $vnp_TxnRef,
            'order_info' => $vnp_OrderInfo,
            'amount' => $data['amount'],
            'bank_code' => $vnp_BankCode,
            'status' => 'pending',
            'raw_data' => json_encode($inputData),
        ]);

        header('Location: ' . $vnp_Url);
        exit;
    }

    function vnpay_return()
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $inputData = array();
        $vnp_SecureHash = $_GET['vnp_SecureHash'];

        foreach ($_GET as $key => $value) {
            if ($key != "vnp_SecureHash" && $key != "vnp_SecureHashType") {
                $inputData[$key] = $value;
            }
        }

        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) $hashData .= '&';
            $hashData .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                return view('payment.success');
            } else {
                return view('payment.fail');
            }
        } else {
            return view('payment.fail');
        }
    }

    // function vnpay_ipn()
    // {
    //     $vnp_HashSecret = env('VNPAY_HASH_SECRET');

    //     $inputData = array();
    //     $vnp_SecureHash = $_GET['vnp_SecureHash'];

    //     foreach ($_GET as $key => $value) {
    //         if ($key != "vnp_SecureHash" && $key != "vnp_SecureHashType") {
    //             $inputData[$key] = $value;
    //         }
    //     }

    //     ksort($inputData);
    //     $hashData = "";
    //     $i = 0;
    //     foreach ($inputData as $key => $value) {
    //         if ($i == 1) $hashData .= '&';
    //         $hashData .= urlencode($key) . "=" . urlencode($value);
    //         $i = 1;
    //     }

    //     $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    //     if ($secureHash === $vnp_SecureHash) {
    //         $orderId = $_GET['vnp_TxnRef'];
    //         $vnp_Amount = $_GET['vnp_Amount'];
    //         $vnp_ResponseCode = $_GET['vnp_ResponseCode'];
    //         $vnp_TransactionNo = $_GET['vnp_TransactionNo'];

    //         // TODO: Truy xuất đơn hàng từ table_abc() bằng $orderId để kiểm tra trạng thái và số tiền
    //         // Nếu hợp lệ, cập nhật trạng thái thành 'đã thanh toán'
    //         $payment = Payment::where('txn_ref', $orderId)->first();

    //         if (!$payment || $payment->amount != $vnp_Amount / 100) {
    //             echo "0|Invalid amount or order not found";
    //             return;
    //         }

    //         if ($vnp_ResponseCode == '00' && $payment->status !== 'paid') {
    //             $payment->update([
    //                 'transaction_no' => $vnp_TransactionNo,
    //                 'bank_code'      => $_GET['vnp_BankCode'] ?? null,
    //                 'pay_date'       => now(),
    //                 'status'         => 'paid',
    //                 'raw_data'       => json_encode($_GET),
    //             ]);
    //             \Log::info('✅ Giao dịch thành công', [
    //                 'txn_ref' => $orderId,
    //                 'amount' => $vnp_Amount / 100,
    //                 'transaction_no' => $vnp_TransactionNo,
    //                 'bank' => $_GET['vnp_BankCode'] ?? null,
    //                 'at' => now()->toDateTimeString()
    //             ]);
    //             echo "1|OK";
    //             return response('1|OK', 200)->header('Content-Type', 'text/plain');
    //         } else {
    //             \Log::warning('❌ Giao dịch thất bại hoặc đã xử lý', [
    //                 'txn_ref' => $orderId,
    //                 'response_code' => $vnp_ResponseCode,
    //                 'status' => $payment->status ?? null
    //             ]);
    //             echo "0|Payment failed or already processed";
    //             return response('0|Invalid Signature', 200)->header('Content-Type', 'text/plain');
    //         }

    //         echo "1|OK";
    //     } else {
    //         echo "0|Invalid Signature";
    //     }
    // }
    function vnpay_ipn()
    {
        Log::info('🔥 Đây là log thử nghiệm từ IPN');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        
        $inputData = array();
        $vnp_SecureHash = $_GET['vnp_SecureHash'];

        // Lấy các tham số từ VNPAY
        foreach ($_GET as $key => $value) {
            if ($key != "vnp_SecureHash" && $key != "vnp_SecureHashType") {
                $inputData[$key] = $value;
            }
        }

        // Sắp xếp các tham số theo thứ tự
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) $hashData .= '&';
            $hashData .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }

        // Kiểm tra checksum
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Khởi tạo dữ liệu trả về
        $returnData = [];

        try {
            // Kiểm tra nếu chữ ký hợp lệ
            if ($secureHash === $vnp_SecureHash) {
                // Lấy thông tin giao dịch
                $orderId = $_GET['vnp_TxnRef'];
                $vnp_Amount = $_GET['vnp_Amount'];
                $vnp_ResponseCode = $_GET['vnp_ResponseCode'];
                $vnp_TransactionNo = $_GET['vnp_TransactionNo'];

                // Truy xuất thông tin đơn hàng từ DB
                $payment = Payment::where('txn_ref', $orderId)->first();

                if (!$payment || $payment->amount != $vnp_Amount / 100) {
                    // Số tiền không hợp lệ hoặc đơn hàng không tìm thấy
                    $returnData['RspCode'] = '04';
                    $returnData['Message'] = 'Invalid amount or order not found';
                    echo json_encode($returnData);
                    return;
                }

                if ($vnp_ResponseCode == '00' && $payment->status !== 'paid') {
                    // Cập nhật trạng thái đơn hàng thành đã thanh toán
                    $payment->update([
                        'transaction_no' => $vnp_TransactionNo,
                        'bank_code'      => $_GET['vnp_BankCode'] ?? null,
                        'pay_date'       => now(),
                        'status'         => 'paid',
                        'raw_data'       => json_encode($_GET),
                    ]);

                    $order_info = $payment['order_info'];
                    $this->completePurchase($order_info);

                    \Log::info('✅ Giao dịch thành công', [
                        'txn_ref' => $orderId,
                        'amount' => $vnp_Amount / 100,
                        'transaction_no' => $vnp_TransactionNo,
                        'bank' => $_GET['vnp_BankCode'] ?? null,
                        'at' => now()->toDateTimeString()
                    ]);

                    $returnData['RspCode'] = '00';
                    $returnData['Message'] = 'Confirm Success';
                    echo json_encode($returnData);
                    return;
                } else {
                    // Giao dịch thất bại hoặc đã xử lý
                    \Log::warning('❌ Giao dịch thất bại hoặc đã xử lý', [
                        'txn_ref' => $orderId,
                        'response_code' => $vnp_ResponseCode,
                        'status' => $payment->status ?? null
                    ]);

                    $returnData['RspCode'] = '02';
                    $returnData['Message'] = 'Payment failed or already processed';
                    echo json_encode($returnData);
                    return;
                }
            } else {
                // Chữ ký không hợp lệ
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid Signature';
                echo json_encode($returnData);
            }
        } catch (Exception $e) {
            // Xử lý ngoại lệ
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknown error';
            echo json_encode($returnData);
        }
    }


    private function completePurchase($order_info) {
        $order_info = $order_info == "" ? '1-buy10-20250422150600' : $order_info;
        [$id_user, $key, $code] = explode('-', $order_info);
        Log::info("Order info $id_user: +$order_info");

        if($key == 'buy3' || $key == 'buy10' || $key == 'buy9999') {
            $candidate = Candidate::where('id', $id_user)->first();
            if ($candidate) {
                $amount = match ($key) {
                    'buy3' => 3,
                    'buy10' => 10,
                    default => 9999,
                };
                Log::info("CV limit updated for candidate $id_user: +$amount");
                $candidate->cv_limit += $amount; 
                $candidate->save();           
            }
        }
    }
}