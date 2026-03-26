<?php

namespace App\Enums;

enum NotificationType: string
{
    case BookingConfirmationNotification = 'booking_confirmation';
    case BookingReminderNotification = 'booking_reminder';
    case BookingCancelledNotification = 'booking_cancelled';
    case PaymentInformationNotification = 'payment_information';
    case MessageInformationNotification = 'message_information';
    case OthersNotificationNotification = 'others_notification';
    case NewReservationReceivedNotification = 'new_reservation_received';
    case ReservationReminderNotification = 'reservation_reminder';
    case ReservationCompletedNotification = 'reservation_completed';
    case ReservationCancelledNotification = 'reservation_cancelled';
    case ExtensionReservationReceivedNotification = 'extension_reservation_received';
    //other notifications
    case ContactSupportNotification = 'contact_support';
    case InfoNotificationNotification = 'info_notification';
    case InfoNotificationWithQrCodeNotification = 'info_notification_with_qrcode';
    
}