<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendWelcomeEmail($email, $name)
    {
        if (!$email) {
            return;
        }

        try {
            Mail::send('emails.welcome', ['name' => $name], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Welcome to TBA Finance Cloud 🎉');
            });
            \Log::info('Email sent to: ' . $email);
        } catch (\Exception $e) {
            \Log::error('Email failed: ' . $e->getMessage());
        }
    }

    public function sendLoanApprovalEmail($email, $name, $loanAmount)
    {
        if (!$email) {
            return;
        }

        try {
            Mail::send('emails.loan-approved', [
                'name' => $name,
                'loanAmount' => $loanAmount
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('✅ Your Loan Has Been Approved!');
            });
        } catch (\Exception $e) {
            \Log::error('Email failed: ' . $e->getMessage());
        }
    }

    public function sendDepositConfirmationEmail($email, $name, $amount)
    {
        if (!$email) {
            return;
        }

        try {
            Mail::send('emails.deposit-confirmation', [
                'name' => $name,
                'amount' => $amount
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('💰 Deposit Confirmed!');
            });
        } catch (\Exception $e) {
            \Log::error('Email failed: ' . $e->getMessage());
        }
    }

    public function sendLoanReminderEmail($email, $name, $amount, $dueDate)
    {
        if (!$email) {
            return;
        }

        try {
            Mail::send('emails.loan-reminder', [
                'name' => $name,
                'amount' => $amount,
                'dueDate' => $dueDate
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('⏰ Loan Repayment Reminder');
            });
        } catch (\Exception $e) {
            \Log::error('Email failed: ' . $e->getMessage());
        }
    }
}