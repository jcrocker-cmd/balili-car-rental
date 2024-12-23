<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Signin;
use App\Models\AddCar;
use App\Models\AdminInfo;
use App\Events\CarBooked;
use App\Models\Admin_Notification;
use App\Models\Client_Notification;
use Illuminate\Support\Facades\Event;
use Mail;
use Session;
use DB;
use Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use App\Notifications\BookingNotification;
use Illuminate\Support\Facades\Notification;

class BookingformsController extends Controller
{ 

    public function booking_route($slug)
    {
        $user_id = Auth::id();
    
        $notificationsUnread = Client_Notification::where('user_id', $user_id)
            ->whereNull('read_at')
            ->get();
        $car_details = AddCar::where('slug', $slug)->first();
        return view('main.bookingforms', compact('car_details','notificationsUnread'));
    }

    public function succes_booking_route()
    {
        $user_id = Auth::id();
    
        $notificationsUnread = Client_Notification::where('user_id', $user_id)
            ->whereNull('read_at')
            ->get();
        return view('main.success-booking', compact('notificationsUnread'));

    }

    public function weekly_booking_route($slug)
    {
        $user_id = Auth::id();
    
        $notificationsUnread = Client_Notification::where('user_id', $user_id)
            ->whereNull('read_at')
            ->get();
        $car_details = AddCar::where('slug', $slug)->first();
        return view('main.weekly-bookingforms', compact('car_details','notificationsUnread'));
    }

    
    public function monthly_booking_route($slug)
    {
        $user_id = Auth::id();
    
        $notificationsUnread = Client_Notification::where('user_id', $user_id)
            ->whereNull('read_at')
            ->get();
        $car_details = AddCar::where('slug', $slug)->first();
        return view('main.monthly-bookingforms', compact('car_details','notificationsUnread'));
    }


    public function booking_submit(Request $request, $slug)
    {

    $car_details = AddCar::where('slug', $slug)->first();
            
        $data = [
            'name' => $request->name,
            'con_num' => $request->con_num,
            'address' => $request->address,
            'con_email' => $request->con_email,
            'mode_del' => $request->mode_del,
            'payment' => $request->payment,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'return_date' => $request->return_date,
            'return_time' => $request->return_time,
            'total_amount_payable' => $request->total_amount_payable,
            'msg' => $request->msg,
            'car_details' => $car_details,
        ];
    
        $data['car_id'] = $car_details->id;
    
        // Save data to database
        $booking = new Booking;
        $booking->name = $data['name'];
        $booking->con_num = $data['con_num'];
        $booking->address = $data['address'];
        $booking->con_email = $data['con_email'];
        $booking->mode_del = $data['mode_del'];
        $booking->payment = $data['payment'];
        $booking->start_date = $data['start_date'];
        $booking->start_time = $data['start_time'];
        $booking->return_date = $data['return_date'];
        $booking->return_time = $data['return_time'];
        $booking->total_amount_payable = $data['total_amount_payable'];
        $booking->msg = $data['msg'];
        $booking->car_id = $data['car_id'];
        $booking->status = 'In progress';
        $booking->form_type = 'Daily Booking Form';
        $booking->save();

        // Create custom notification
        $notification = new Admin_Notification();
        $notification->car_id = $data['car_id'];
        $notification->booking_id = $booking->id;
        $notification->message = 'Car has been booked';
        $notification->save();

        // Update car status
        $car = AddCar::findOrFail($car_details->id);
        $car->status = 'In progress';
        $car->save();
    
        // Send email notification
        Mail::send('main.daily-email-template', ['data' => $data], function($message) use ($data) {
            $message->to('marzbalskie@gmail.com');
            $message->cc($data['con_email']);
            $message->subject('Daily Booking Form');  
        });


        return redirect('/success-booking')->with('success', 'You`ve Successfully Book your car.');


    }


    public function weekly_booking_submit(Request $request, $slug)
    {


    $car_details = AddCar::where('slug', $slug)->first();
            
        $data = [
            'name' => $request->name,
            'con_num' => $request->con_num,
            'address' => $request->address,
            'con_email' => $request->con_email,
            'mode_del' => $request->mode_del,
            'payment' => $request->payment,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'return_date' => $request->return_date,
            'return_time' => $request->return_time,
            'total_amount_payable' => $request->total_amount_payable,
            'msg' => $request->msg,
            'car_details' => $car_details,
        ];
    
        $data['car_id'] = $car_details->id;
    
        // Save data to database
        $booking = new Booking;
        $booking->name = $data['name'];
        $booking->con_num = $data['con_num'];
        $booking->address = $data['address'];
        $booking->con_email = $data['con_email'];
        $booking->mode_del = $data['mode_del'];
        $booking->payment = $data['payment'];
        $booking->start_date = $data['start_date'];
        $booking->start_time = $data['start_time'];
        $booking->return_date = $data['return_date'];
        $booking->return_time = $data['return_time'];
        $booking->total_amount_payable = $data['total_amount_payable'];
        $booking->msg = $data['msg'];
        $booking->car_id = $data['car_id'];
        $booking->status = 'In progress';
        $booking->form_type = 'Weekly Booking Form';  
        $booking->save();

        // Create custom notification
        $notification = new Admin_Notification();
        $notification->car_id = $data['car_id'];
        $notification->booking_id = $booking->id;
        $notification->message = 'Car has been booked';
        $notification->save();
        
        // Update car status
        $car = AddCar::findOrFail($car_details->id);
        $car->status = 'In progress';
        $car->save();
    
        // Send email notification
        Mail::send('main.weekly-email-template', ['data' => $data], function($message) use ($data) {
            $message->to('marzbalskie@gmail.com');
            $message->cc($data['con_email']);
            $message->subject('Weekly Booking Form');
        
        });

      return redirect('/success-booking')->with('success', 'You`ve Successfully Book your car.');


      
    }


    public function monthly_booking_submit(Request $request, $slug)
    {


    $car_details = AddCar::where('slug', $slug)->first();
   
        $data = [
            'name' => $request->name,
            'con_num' => $request->con_num,
            'address' => $request->address,
            'con_email' => $request->con_email,
            'mode_del' => $request->mode_del,
            'payment' => $request->payment,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'return_date' => $request->return_date,
            'return_time' => $request->return_time,
            'total_amount_payable' => $request->total_amount_payable,
            'msg' => $request->msg,
            'car_details' => $car_details,
        ];
    
        $data['car_id'] = $car_details->id;
    
        // Save data to database
        $booking = new Booking;
        $booking->name = $data['name'];
        $booking->con_num = $data['con_num'];
        $booking->address = $data['address'];
        $booking->con_email = $data['con_email'];
        $booking->mode_del = $data['mode_del'];
        $booking->payment = $data['payment'];
        $booking->start_date = $data['start_date'];
        $booking->start_time = $data['start_time'];
        $booking->return_date = $data['return_date'];
        $booking->return_time = $data['return_time'];
        $booking->total_amount_payable = $data['total_amount_payable'];
        $booking->msg = $data['msg'];
        $booking->car_id = $data['car_id'];
        $booking->status = 'In progress';
        $booking->form_type = 'Monthly Booking Form';
        $booking->save();

        // Create custom notification
        $notification = new Admin_Notification();
        $notification->car_id = $data['car_id'];
        $notification->booking_id = $booking->id;
        $notification->message = 'Car has been booked';
        $notification->save();
        
        // Update car status
        $car = AddCar::findOrFail($car_details->id);
        $car->status = 'In progress';
        $car->save();
    
        // Send email notification
        Mail::send('main.monthly-email-template', ['data' => $data], function($message) use ($data) {
            $message->to('marzbalskie@gmail.com');
            $message->cc($data['con_email']);
            $message->subject('Monthly Booking Form');
        });


        return redirect('/success-booking')->with('success', 'You`ve Successfully Book your car.');


    }


    public function sendWhatsAppMessage($whatsappNumber, $data)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
    
        // Debugging: Check if SID and Token are being loaded
        if (empty($sid) || empty($token)) {
            \Log::error('Twilio SID or Token not set in .env');
            return; // Exit the method if SID or Token is not set
        }
    
        $twilio = new Client($sid, $token);
    
        $messageBody = "Hi {$data['name']},\n"
            . "Your booking details are as follows:\n"
            . "Car: {$data['car_details']->name}\n"
            . "Pickup: {$data['start_date']} at {$data['start_time']}\n"
            . "Return: {$data['return_date']} at {$data['return_time']}\n"
            . "Total: {$data['total_amount_payable']}";
    
        try {
            $twilio->messages->create(
                "whatsapp:" . $whatsappNumber,
                [
                    'from' => env('TWILIO_WHATSAPP_NUMBER'),
                    'body' => $messageBody
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Twilio WhatsApp Message Error: ' . $e->getMessage());
            // Optionally, return a response or throw an error
        }
    }


    public function confirmBooking($id)
    {
        $booking = Booking::find($id);

        if ($booking) {
            $booking->status = 'Confirmed';
            $booking->save();

            $car = $booking->car;
            $car->status = 'Rented';
            $car->save();

            // Create notification
            $notification = new Client_Notification;
            $notification->user_id = $booking->user_id;
            $notification->car_id = $booking->car_id;
            $notification->booking_id = $booking->id;
            $notification->message = 'Booking has been Confirmed';
            $notification->save();

            return redirect()->back()->with('status', 'Booking confirmed successfully.');
        }

        return redirect()->back()->with('status', 'Booking not found.');
    }

        public function declineBooking($id)
    {
        $booking = Booking::find($id);
        $booking->status = 'Declined';
        $booking->save();

        $car = $booking->car;
        $car->status = 'Available';
        $car->save();

        // Create notification
        $notification = new Client_Notification;
        $notification->user_id = $booking->user_id;
        $notification->car_id = $booking->car_id;
        $notification->booking_id = $booking->id;
        $notification->message = 'Booking has been Declined';
        $notification->save();

        return redirect('/bookings')->with('success', 'Booking declined.');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::find($id);
        $booking->status = 'Cancelled';
        $booking->save();

        $car = $booking->car;
        $car->status = 'Available';
        $car->save();

        // Create notification
        $notification = new Admin_Notification;
        $notification->user_id = $booking->user_id;
        $notification->car_id = $booking->car_id;
        $notification->booking_id = $booking->id;
        $notification->message = 'Booking has been cancelled';
        $notification->save();

        return redirect('/account')->with('status', 'Booking Cancelled.');
    }

    // NOTIFICATIONS






    public function db_bookings()
    {

    $notificationsUnread = Admin_Notification::whereNull('read_at')->get();

      $booking = Booking::with(['car', 'user'])
      ->get();

      // DAY
      $daily_bookings = DB::table('bookingform')
          ->select(DB::raw('COUNT(*) as count, DATE(created_at) as day'))
          ->groupBy('day')
          ->get();

      $days = [];
      $day_booking_counts = [];

      foreach ($daily_bookings as $bookings) {
          $days[] = date("F j, Y", strtotime($bookings->day));
          $day_booking_counts [] = $bookings->count;
      }


      // WEEK
      $weekly_bookings = DB::table('bookingform')
          ->select(DB::raw('COUNT(*) as count, DATE(DATE_FORMAT(created_at, "%Y-%m-%d") - INTERVAL DAYOFWEEK(created_at) - 1 DAY) as week_start_date'))
          ->groupBy('week_start_date')
          ->get();

      $weeks = [];
      $week_booking_counts  = [];

      foreach ($weekly_bookings as $bookings) {
          $weeks[] = 'Week of '.date("F j, Y", strtotime($bookings->week_start_date));
          $week_booking_counts [] = $bookings->count;
      }

      // MONTH
      $monthly_bookings = DB::table('bookingform')
          ->select(DB::raw('COUNT(*) as count, DATE(DATE_FORMAT(created_at, "%Y-%m-01")) as month_start_date'))
          ->groupBy('month_start_date')
          ->get();

      $months = [];
      $month_booking_counts  = [];

      foreach ($monthly_bookings as $bookings) {
          $months[] = date("F Y", strtotime($bookings->month_start_date));
          $month_booking_counts [] = $bookings->count;
      }

      // YEAR
      $yearly_bookings = DB::table('bookingform')
      ->select(DB::raw('COUNT(*) as count, YEAR(created_at) as year'))
      ->groupBy('year')
      ->get();

      $years = [];
      $year_booking_counts = [];

      foreach ($yearly_bookings as $bookings) {
      $years[] = $bookings->year;
      $year_booking_counts[] = $bookings->count;
      }

      return view ('dashboard.booking', compact('notificationsUnread','day_booking_counts', 'week_booking_counts', 'month_booking_counts','year_booking_counts','days', 'weeks', 'months','years','booking',));
      }


    public function db_booking_delete($id)
    {
        $booking = Booking::find($id);
        $booking -> delete();
        Session::flash('status','You`ve successfully deleted a booking!');
        return redirect('/bookings')->with('booking', $booking); 
    }

    public function db_booking_ajaxview($id)
    {
        $booking = Booking::with('car')->find($id);
        $front_license = asset('images/license/front/' . $booking->front_license);
        $back_license = asset('images/license/back/' . $booking->back_license);
        return response()->json([
            'status' => 200,
            'booking' => $booking,
            'front_license' => $front_license,
            'back_license' => $back_license,
        ]);
    }

    public function user_booking_ajaxview($id)
    {
        $booking = Booking::with('car')->find($id);
        $front_license = asset('/images/license/front/' . $booking->front_license);
        $back_license = asset('images/license/back/' . $booking->back_license);
        return response()->json([
            'status' => 200,
            'booking' => $booking,
            'front_license' => $front_license,
            'back_license' => $back_license,
        ]);
    }
}
