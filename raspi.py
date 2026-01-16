#!/usr/bin/env python3
# tollgate_final_complete.py - Complete Tollgate System with 20s Delay

import RPi.GPIO as GPIO
import time
import threading
import json
import requests
from datetime import datetime, timedelta
from flask import Flask, jsonify, request
from flask_cors import CORS
import signal
import sys
import atexit

# ============================================
# GPIO Configuration
# ============================================
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

# GPIO Pin Configuration
PINS = {
    "SERVO_RFID": 18,    # GPIO18 - Pin 12
    "SERVO_CASH": 23,    # GPIO23 - Pin 16
    "LED_RED_RFID": 17,  # GPIO17 - Pin 11
    "LED_GREEN_RFID": 27,# GPIO27 - Pin 13
    "LED_RED_CASH": 5,   # GPIO5 - Pin 29
    "LED_GREEN_CASH": 6, # GPIO6 - Pin 31
    "BUZZER": 22         # GPIO22 - Pin 15
}

# Firebase Configuration
FIREBASE_URL = "https://tollgate-13ae4-default-rtdb.firebaseio.com"
TOLL_FEE = 156

# Flask API Server
app = Flask(__name__)
CORS(app)

# RFID Reader
RFID_AVAILABLE = False
try:
    import mfrc522
    from mfrc522 import MFRC522
    RFID_AVAILABLE = True
    print("✅ RFID Module imported successfully")
except ImportError as e:
    print(f"⚠️  RFID Module not available: {e}")
    print("⚠️  Running in simulation mode")

# ============================================
# Improved MFRC522 Reader Class with Better Handling
# ============================================
class ImprovedMFRC522:
    def __init__(self):
        if not RFID_AVAILABLE:
            self.reader = None
            return
            
        try:
            self.reader = MFRC522()
            self.last_read_time = 0
            self.read_delay = 0.1  # 100ms delay between reads
            self.last_card_id = None
            self.last_uid = None
            self.debounce_time = 2  # 2 seconds debounce for same card
            print("✅ MFRC522 Reader initialized")
        except Exception as e:
            print(f"❌ MFRC522 initialization error: {e}")
            self.reader = None
    
    def read_no_block(self):
        """Read RFID card without blocking - returns 8-digit card ID"""
        if not self.reader or not RFID_AVAILABLE:
            return None, None
            
        try:
            # Check if enough time has passed since last read
            current_time = time.time()
            if current_time - self.last_read_time < self.read_delay:
                return None, None
            
            # Scan for cards
            (status, tag_type) = self.reader.MFRC522_Request(self.reader.PICC_REQIDL)
            
            if status != self.reader.MI_OK:
                return None, None
            
            # Get the UID of the card
            (status, uid) = self.reader.MFRC522_Anticoll()
            
            if status != self.reader.MI_OK:
                return None, None
            
            # Convert UID to hexadecimal string
            card_id_hex = ''.join(['{:02X}'.format(x) for x in uid])
            
            # Create consistent 8-digit ID
            if len(card_id_hex) >= 8:
                card_id = card_id_hex[-8:].upper()  # Last 8 hex characters
            else:
                card_id = card_id_hex.zfill(8).upper()
            
            # Check if this is the same card as last time (debounce)
            if (card_id == self.last_card_id and 
                current_time - self.last_read_time < self.debounce_time):
                return None, None
            
            # Update tracking
            self.last_card_id = card_id
            self.last_uid = uid
            self.last_read_time = current_time
            
            # Stop crypto to allow new reads
            try:
                self.reader.MFRC522_SelectTag(uid)
                self.reader.MFRC522_StopCrypto1()
            except Exception as e:
                # This is normal, just continue
                pass
            
            print(f"📱 RFID Read successful: {card_id}")
            return card_id, ""
            
        except Exception as e:
            # Don't spam errors, only log occasionally
            if current_time - getattr(self, 'last_error_time', 0) > 5:
                print(f"⚠️  RFID Read error: {e}")
                self.last_error_time = current_time
            return None, None
    
    def reset_last_card(self):
        """Reset last card tracking to allow immediate re-read"""
        self.last_card_id = None
        self.last_uid = None
        print("🔄 RFID reader reset - ready for new card")

# ============================================
# System Class - WITH 20s DELAY
# ============================================
class TollgateSystem:
    def __init__(self):
        self.running = True
        self.registration_mode = False
        self.last_registration_card = None
        self.scan_delay = 20  # 20 seconds delay between scans
        self.cleaned_up = False  # Track if cleanup has been done
        
        # Card cooldown tracking
        self.card_cooldowns = {}  # card_id: last_scan_time
        
        # Setup GPIO
        self.setup_gpio()
        
        # Initialize RFID Reader
        self.rfid_reader = ImprovedMFRC522()
        
        # Start RFID monitoring
        if self.rfid_reader.reader:
            self.rfid_thread = threading.Thread(target=self.scan_rfid_loop, daemon=True)
            self.rfid_thread.start()
            print("✅ RFID monitoring thread started")
        else:
            print("⚠️  Running without RFID hardware")
        
        # Start website command listener
        self.website_thread = threading.Thread(target=self.listen_website_commands, daemon=True)
        self.website_thread.start()
        
        # Start notification cleaner
        self.cleaner_thread = threading.Thread(target=self.cleanup_notifications, daemon=True)
        self.cleaner_thread.start()
        
        print("=" * 60)
        print("🚗 TAP-ONLY TOLLGATE SYSTEM - FIXED VERSION")
        print("=" * 60)
        print(f"RFID Status: {'CONNECTED' if RFID_AVAILABLE else 'SIMULATION MODE'}")
        print(f"Cooldown: {self.scan_delay} seconds between card scans")
        print(f"Toll Fee: ₱{TOLL_FEE}")
        print(f"API Server: http://10.20.165.164:5000")
        print("=" * 60)
        print("\n📱 READY FOR OPERATION:")
        print("   1. Normal use: Just tap RFID cards")
        print("   2. Registration: Use website 'Tap to Register'")
        print("   3. Cash payments: Process via website")
        print("\n⏱️  Features:")
        print("   • 20-second cooldown per card")
        print("   • Real-time website updates")
        print("   • Automatic gate control")
        print("   • System notifications")
        print("=" * 60)
    
    def setup_gpio(self):
        """Setup GPIO pins"""
        # Setup servos
        GPIO.setup(PINS["SERVO_RFID"], GPIO.OUT)
        GPIO.setup(PINS["SERVO_CASH"], GPIO.OUT)
        
        self.servo_rfid = GPIO.PWM(PINS["SERVO_RFID"], 50)
        self.servo_cash = GPIO.PWM(PINS["SERVO_CASH"], 50)
        self.servo_rfid.start(0)
        self.servo_cash.start(0)
        
        # Setup LEDs
        for pin in ["LED_RED_RFID", "LED_GREEN_RFID", "LED_RED_CASH", "LED_GREEN_CASH"]:
            GPIO.setup(PINS[pin], GPIO.OUT)
            GPIO.output(PINS[pin], GPIO.LOW)
        
        # Setup buzzer
        GPIO.setup(PINS["BUZZER"], GPIO.OUT)
        GPIO.output(PINS["BUZZER"], GPIO.LOW)
        
        # Close all gates
        self.close_gate("rfid")
        self.close_gate("cash")
        print("✅ GPIO setup complete")
    
    def can_scan_card(self, card_id):
        """Check if card can be scanned (20-second cooldown)"""
        if card_id not in self.card_cooldowns:
            return True
        
        last_scan = self.card_cooldowns[card_id]
        time_since_last_scan = (datetime.now() - last_scan).total_seconds()
        
        if time_since_last_scan < self.scan_delay:
            remaining = int(self.scan_delay - time_since_last_scan)
            print(f"⏳ Card {card_id} on cooldown: {remaining}s remaining")
            return False
        
        return True
    
    def update_card_cooldown(self, card_id):
        """Update card cooldown timestamp"""
        self.card_cooldowns[card_id] = datetime.now()
    
    def scan_rfid_loop(self):
        """Main RFID scanning loop - FIXED VERSION"""
        print("📱 RFID Scanner started...")
        
        while self.running:
            try:
                # Read RFID card
                card_id, text = self.rfid_reader.read_no_block()
                
                if card_id:
                    print(f"📱 RFID Detected: {card_id}")
                    
                    # Check cooldown
                    if not self.can_scan_card(card_id):
                        self.beep(0.5)  # Error beep
                        # Reset reader to allow immediate re-scan after cooldown
                        self.rfid_reader.reset_last_card()
                        time.sleep(1)
                        continue
                    
                    print(f"✅ Processing card: {card_id}")
                    self.beep(0.2)
                    
                    # Update cooldown
                    self.update_card_cooldown(card_id)
                    
                    # Check if we're in registration mode
                    if self.registration_mode:
                        print(f"🎯 Card for registration: {card_id}")
                        self.last_registration_card = card_id
                        self.registration_mode = False
                        self.beep(0.1, 2)  # Success beep
                        
                        # Send immediate notification
                        self.send_notification('registration', card_id, 
                                             True, 'Card scanned for registration')
                        
                        # Reset reader to allow immediate re-scan
                        self.rfid_reader.reset_last_card()
                    else:
                        # Normal payment mode
                        self.process_payment(card_id)
                    
                    # Small delay before next scan
                    time.sleep(0.5)
                
                time.sleep(0.05)  # Small delay to prevent CPU overuse
                
            except Exception as e:
                print(f"❌ RFID Loop Error: {e}")
                time.sleep(1)
    
    def process_payment(self, card_id):
        """Process RFID card payment"""
        print(f"💳 Processing payment for card: {card_id}")
        
        try:
            # Check card in Firebase
            response = requests.get(f"{FIREBASE_URL}/rfidCards/{card_id}.json", timeout=3)
            card_data = response.json()
            
            if not card_data:
                print(f"❌ Card {card_id} not registered")
                self.beep(0.5)  # Error beep
                
                # Send notification
                self.send_notification('payment', card_id, False, 'Card not registered')
                
                # Reset reader to allow immediate re-scan
                self.rfid_reader.reset_last_card()
                return
            
            if card_data.get('status') != 'active':
                status = card_data.get('status', 'unknown')
                print(f"❌ Card {card_id} not active (status: {status})")
                self.beep(0.5)
                
                self.send_notification('payment', card_id, False, f'Card is {status}')
                self.rfid_reader.reset_last_card()
                return
            
            balance = float(card_data.get('balance', 0))
            
            if balance < TOLL_FEE:
                print(f"❌ Insufficient balance: ₱{balance} (needs ₱{TOLL_FEE})")
                self.beep(0.5)
                
                self.send_notification('payment', card_id, False, 
                                     f'Insufficient balance: ₱{balance}')
                self.rfid_reader.reset_last_card()
                return
            
            # Deduct toll fee
            new_balance = balance - TOLL_FEE
            update_data = {
                'balance': new_balance,
                'lastUsed': datetime.now().isoformat()
            }
            
            # Update Firebase
            update_response = requests.patch(f"{FIREBASE_URL}/rfidCards/{card_id}.json", 
                                           json=update_data, timeout=3)
            
            if update_response.status_code not in [200, 204]:
                print(f"❌ Failed to update card balance")
                self.beep(0.5)
                self.rfid_reader.reset_last_card()
                return
            
            # Record transaction
            transaction = {
                'type': 'rfid',
                'cardId': card_id,
                'cardHolder': card_data.get('holder', 'Unknown'),
                'amount': TOLL_FEE,
                'previousBalance': balance,
                'newBalance': new_balance,
                'timestamp': datetime.now().isoformat(),
                'status': 'success',
                'gate': 'rfid'
            }
            
            requests.post(f"{FIREBASE_URL}/transactions.json", 
                         json=transaction, timeout=3)
            
            print(f"✅ Payment successful! New balance: ₱{new_balance}")
            
            # Send success notification
            self.send_notification('payment', card_id, True, 
                                 f'Payment successful! New balance: ₱{new_balance}')
            
            # Open RFID gate
            self.open_gate("rfid")
            
            # Reset reader after successful payment
            time.sleep(1)
            self.rfid_reader.reset_last_card()
            
        except requests.exceptions.Timeout:
            print("❌ Firebase connection timeout")
            self.beep(0.5)
            self.send_notification('system', card_id, False, 'Connection timeout')
            self.rfid_reader.reset_last_card()
        except Exception as e:
            print(f"❌ Payment error: {e}")
            self.beep(0.5)
            self.send_notification('payment', card_id, False, f'Error: {str(e)[:50]}')
            self.rfid_reader.reset_last_card()
    
    def send_notification(self, notif_type, card_id, success, message):
        """Send notification to Firebase for website to pick up"""
        try:
            notification = {
                'type': notif_type,
                'cardId': card_id,
                'success': success,
                'message': message,
                'timestamp': datetime.now().isoformat()
            }
            
            # Store in Firebase
            notification_id = f"notif_{int(time.time())}_{card_id}"
            requests.put(f"{FIREBASE_URL}/notifications/{notification_id}.json", 
                        json=notification, timeout=2)
            
        except Exception as e:
            print(f"⚠️  Failed to send notification: {e}")
    
    def process_cash_payment(self, amount_paid):
        """Process cash payment from website"""
        try:
            if amount_paid < TOLL_FEE:
                return False, f"Amount must be at least ₱{TOLL_FEE}"
            
            change = amount_paid - TOLL_FEE
            
            # Record transaction
            transaction = {
                'type': 'cash',
                'amountPaid': amount_paid,
                'change': change,
                'timestamp': datetime.now().isoformat(),
                'status': 'success',
                'gate': 'cash'
            }
            
            response = requests.post(f"{FIREBASE_URL}/transactions.json", 
                                   json=transaction, timeout=3)
            
            print(f"💰 Cash payment: ₱{amount_paid}, Change: ₱{change}")
            
            # Send notification
            self.send_notification('payment', 'CASH', True, 
                                 f'Cash payment: ₱{amount_paid}, Change: ₱{change}')
            
            # Open cash gate
            self.open_gate("cash")
            
            return True, f"Payment processed. Change: ₱{change}"
            
        except Exception as e:
            print(f"❌ Cash payment error: {e}")
            return False, f"Error: {str(e)}"
    
    def open_gate(self, gate_type):
        """Open gate"""
        print(f"🚪 Opening {gate_type.upper()} gate...")
        
        if gate_type == "rfid":
            # Open RFID gate
            self.servo_rfid.ChangeDutyCycle(7.5)  # 90 degrees
            time.sleep(0.5)
            self.servo_rfid.ChangeDutyCycle(0)
            
            # Update LEDs
            GPIO.output(PINS["LED_RED_RFID"], GPIO.LOW)
            GPIO.output(PINS["LED_GREEN_RFID"], GPIO.HIGH)
            
            print("✅ RFID GATE OPEN")
        else:  # cash
            # Open Cash gate
            self.servo_cash.ChangeDutyCycle(7.5)  # 90 degrees
            time.sleep(0.5)
            self.servo_cash.ChangeDutyCycle(0)
            
            # Update LEDs
            GPIO.output(PINS["LED_RED_CASH"], GPIO.LOW)
            GPIO.output(PINS["LED_GREEN_CASH"], GPIO.HIGH)
            
            print("✅ CASH GATE OPEN")
        
        # Sound buzzer
        self.beep()
        
        # Auto-close after 5 seconds
        threading.Thread(target=self.auto_close_gate, 
                        args=(gate_type,), daemon=True).start()
    
    def close_gate(self, gate_type):
        """Close gate"""
        if gate_type == "rfid":
            self.servo_rfid.ChangeDutyCycle(2.5)  # 0 degrees
            time.sleep(0.5)
            self.servo_rfid.ChangeDutyCycle(0)
            GPIO.output(PINS["LED_RED_RFID"], GPIO.HIGH)
            GPIO.output(PINS["LED_GREEN_RFID"], GPIO.LOW)
        else:  # cash
            self.servo_cash.ChangeDutyCycle(2.5)  # 0 degrees
            time.sleep(0.5)
            self.servo_cash.ChangeDutyCycle(0)
            GPIO.output(PINS["LED_RED_CASH"], GPIO.HIGH)
            GPIO.output(PINS["LED_GREEN_CASH"], GPIO.LOW)
    
    def auto_close_gate(self, gate_type, delay=5):
        """Auto-close gate"""
        time.sleep(delay)
        self.close_gate(gate_type)
        print(f"⏹️  {gate_type.upper()} GATE CLOSED")
    
    def beep(self, duration=0.1, times=1):
        """Sound buzzer"""
        for _ in range(times):
            GPIO.output(PINS["BUZZER"], GPIO.HIGH)
            time.sleep(duration)
            GPIO.output(PINS["BUZZER"], GPIO.LOW)
            if times > 1:
                time.sleep(0.05)
    
    def listen_website_commands(self):
        """Listen for commands from website"""
        print("📡 Listening for website commands...")
        
        while self.running:
            try:
                # Check for cash payments
                response = requests.get(f"{FIREBASE_URL}/cashPayments.json", timeout=5)
                if response.status_code == 200:
                    payments = response.json()
                    
                    if payments:
                        latest_key = list(payments.keys())[-1]
                        latest_payment = payments[latest_key]
                        
                        if not latest_payment.get('processed', False):
                            print(f"💰 Processing cash payment: ₱{latest_payment.get('amount', 0)}")
                            
                            # Process cash payment
                            success, message = self.process_cash_payment(
                                float(latest_payment.get('amount', 0))
                            )
                            
                            # Mark as processed
                            requests.patch(f"{FIREBASE_URL}/cashPayments/{latest_key}.json", 
                                         json={'processed': True, 'result': message})
                
                time.sleep(1)
                
            except Exception as e:
                print(f"Website command error: {e}")
                time.sleep(2)
    
    def cleanup_notifications(self):
        """Clean up old notifications"""
        while self.running:
            try:
                # Delete notifications older than 1 hour
                cutoff = (datetime.now() - timedelta(hours=1)).isoformat()
                
                response = requests.get(f"{FIREBASE_URL}/notifications.json", timeout=5)
                if response.status_code == 200:
                    notifications = response.json()
                    
                    if notifications:
                        for key, notif in notifications.items():
                            if notif.get('timestamp', '') < cutoff:
                                requests.delete(f"{FIREBASE_URL}/notifications/{key}.json")
                
                time.sleep(300)  # Clean every 5 minutes
                
            except Exception as e:
                print(f"Cleanup error: {e}")
                time.sleep(60)
    
    def cleanup(self):
        """Cleanup GPIO - Safe version that prevents double cleanup"""
        if self.cleaned_up:
            print("⚠️  Cleanup already performed, skipping...")
            return
            
        print("\n🛑 Cleaning up GPIO...")
        self.running = False
        
        # Stop servos
        try:
            self.servo_rfid.stop()
            self.servo_cash.stop()
        except:
            pass
        
        # Turn off all outputs
        try:
            GPIO.output(PINS["LED_RED_RFID"], GPIO.LOW)
            GPIO.output(PINS["LED_GREEN_RFID"], GPIO.LOW)
            GPIO.output(PINS["LED_RED_CASH"], GPIO.LOW)
            GPIO.output(PINS["LED_GREEN_CASH"], GPIO.LOW)
            GPIO.output(PINS["BUZZER"], GPIO.LOW)
        except:
            pass
        
        # Cleanup GPIO once
        try:
            GPIO.cleanup()
        except Exception as e:
            print(f"⚠️  GPIO cleanup error: {e}")
        
        self.cleaned_up = True
        print("✅ GPIO cleanup complete")

# ============================================
# Flask API Routes
# ============================================
system = None

def cleanup_resources():
    """Global cleanup function"""
    global system
    if system:
        system.cleanup()

@app.route('/')
def home():
    return jsonify({
        "message": "Tap-Only Tollgate System", 
        "status": "running", 
        "rfid": RFID_AVAILABLE,
        "version": "2.0"
    })

@app.route('/api/status')
def status():
    rfid_status = "connected" if system and system.rfid_reader and system.rfid_reader.reader else "disconnected"
    return jsonify({
        "rfid": rfid_status,
        "mode": "tap-only",
        "cooldown": "20 seconds",
        "cards_in_cooldown": len(system.card_cooldowns) if system else 0,
        "timestamp": datetime.now().isoformat(),
        "system": "operational"
    })

@app.route('/api/test-gate/<gate>', methods=['POST'])
def test_gate(gate):
    if not system:
        return jsonify({"error": "System not ready"}), 500
    
    if gate not in ['rfid', 'cash']:
        return jsonify({"error": "Invalid gate"}), 400
    
    system.open_gate(gate)
    return jsonify({"success": True, "message": f"{gate} gate opened"})

@app.route('/api/scan-rfid', methods=['POST'])
def scan_rfid():
    """Scan for registration"""
    if not system or not system.rfid_reader or not system.rfid_reader.reader:
        return jsonify({"error": "RFID not available"}), 500
    
    try:
        print("🔍 Starting registration scan...")
        system.registration_mode = True
        system.last_registration_card = None
        
        # Reset reader to allow immediate scan
        system.rfid_reader.reset_last_card()
        
        # Wait for card scan (30 seconds timeout)
        start_time = time.time()
        while time.time() - start_time < 30:
            if system.last_registration_card:
                card_id = system.last_registration_card
                print(f"✅ Registration card scanned: {card_id}")
                
                return jsonify({
                    "success": True,
                    "card_id": card_id,
                    "message": "RFID card scanned successfully"
                })
            time.sleep(0.1)
        
        # Timeout
        system.registration_mode = False
        return jsonify({
            "success": False,
            "message": "No card scanned within 30 seconds"
        })
        
    except Exception as e:
        system.registration_mode = False
        return jsonify({"error": str(e)}), 500

@app.route('/api/get-cooldown-status', methods=['GET'])
def get_cooldown_status():
    """Get cooldown status for all cards"""
    if not system:
        return jsonify({"error": "System not ready"}), 500
    
    cooldowns = {}
    now = datetime.now()
    
    for card_id, last_scan in system.card_cooldowns.items():
        time_since = (now - last_scan).total_seconds()
        if time_since < system.scan_delay:
            remaining = int(system.scan_delay - time_since)
            cooldowns[card_id] = remaining
    
    return jsonify({
        "success": True,
        "cooldowns": cooldowns,
        "cooldown_duration": system.scan_delay,
        "total_on_cooldown": len(cooldowns)
    })

@app.route('/api/reset-cooldown/<card_id>', methods=['POST'])
def reset_cooldown(card_id):
    """Reset cooldown for a specific card"""
    if not system:
        return jsonify({"error": "System not ready"}), 500
    
    if card_id in system.card_cooldowns:
        del system.card_cooldowns[card_id]
    
    return jsonify({
        "success": True,
        "message": f"Cooldown reset for card {card_id}"
    })

@app.route('/api/test-servo/<pin>', methods=['POST'])
def test_servo(pin):
    """Test servo on specific pin"""
    try:
        pin_num = int(pin)
        GPIO.setup(pin_num, GPIO.OUT)
        pwm = GPIO.PWM(pin_num, 50)
        pwm.start(0)
        
        # Open
        pwm.ChangeDutyCycle(7.5)
        time.sleep(1)
        pwm.ChangeDutyCycle(0)
        
        # Close
        time.sleep(1)
        pwm.ChangeDutyCycle(2.5)
        time.sleep(1)
        pwm.ChangeDutyCycle(0)
        
        pwm.stop()
        return jsonify({"success": True, "message": f"Servo test on GPIO{pin} complete"})
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/api/simulate-tap', methods=['POST'])
def simulate_tap():
    """Simulate an RFID tap for testing"""
    if not system:
        return jsonify({"error": "System not ready"}), 500
    
    data = request.json
    card_id = data.get('card_id', 'A3F5892C')
    
    print(f"🎮 Simulating tap for card: {card_id}")
    
    # Process as if real tap
    system.update_card_cooldown(card_id)
    system.process_payment(card_id)
    
    return jsonify({
        "success": True,
        "message": f"Simulated tap for card {card_id}",
        "card_id": card_id
    })

@app.route('/api/process-cash', methods=['POST'])
def process_cash():
    """Process cash payment directly"""
    if not system:
        return jsonify({"error": "System not ready"}), 500
    
    try:
        data = request.json
        amount = float(data.get('amount', 0))
        
        if amount < TOLL_FEE:
            return jsonify({
                "success": False,
                "message": f"Amount must be at least ₱{TOLL_FEE}"
            })
        
        success, message = system.process_cash_payment(amount)
        
        return jsonify({
            "success": success,
            "message": message
        })
        
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/api/reset-reader', methods=['POST'])
def reset_reader():
    """Reset RFID reader to allow immediate re-scan"""
    if not system or not system.rfid_reader:
        return jsonify({"error": "RFID not available"}), 500
    
    system.rfid_reader.reset_last_card()
    
    return jsonify({
        "success": True,
        "message": "RFID reader reset successfully"
    })

@app.route('/api/clear-notifications', methods=['POST'])
def clear_notifications():
    """Clear all notifications"""
    try:
        response = requests.delete(f"{FIREBASE_URL}/notifications.json", timeout=2)
        if response.status_code in [200, 204]:
            return jsonify({"success": True, "message": "Notifications cleared"})
        else:
            return jsonify({"error": "Failed to clear notifications"}), 500
    except Exception as e:
        return jsonify({"error": str(e)}), 500

# ============================================
# Signal Handlers for Clean Shutdown
# ============================================
def signal_handler(sig, frame):
    """Handle shutdown signals"""
    print('\n\n⏹️  Shutdown signal received...')
    cleanup_resources()
    sys.exit(0)

# Register signal handlers
signal.signal(signal.SIGINT, signal_handler)
signal.signal(signal.SIGTERM, signal_handler)

# Register atexit handler
atexit.register(cleanup_resources)

# ============================================
# Main Function
# ============================================
def main():
    global system
    
    try:
        # Create system
        system = TollgateSystem()
        
        print("\n🌐 Starting API server on http://0.0.0.0:5000")
        print("   Available Endpoints:")
        print("   - GET  /api/status                 - System status")
        print("   - POST /api/test-gate/rfid         - Test RFID gate")
        print("   - POST /api/test-gate/cash         - Test cash gate")
        print("   - POST /api/scan-rfid              - Scan for registration")
        print("   - GET  /api/get-cooldown-status    - Check card cooldowns")
        print("   - POST /api/reset-cooldown/<id>    - Reset card cooldown")
        print("   - POST /api/process-cash           - Process cash payment")
        print("   - POST /api/reset-reader           - Reset RFID reader")
        print("   - POST /api/simulate-tap           - Simulate RFID tap")
        print("   - POST /api/clear-notifications    - Clear notifications")
        print("\n🏁 SYSTEM READY:")
        print("   RFID: Tap cards anytime (20s cooldown)")
        print("   Cash: Use website to process payments")
        print("   Gates: Auto-open and close")
        print("   Website: Real-time updates")
        print("\n📢 Press Ctrl+C to stop the system\n")
        
        # Run Flask server
        try:
            from waitress import serve
            serve(app, host='0.0.0.0', port=5000)
        except ImportError:
            print("⚠️  Waitress not found, using development server")
            app.run(host='0.0.0.0', port=5000, debug=False, threaded=True)
        
    except KeyboardInterrupt:
        print("\n⏹️  Keyboard interrupt received...")
    except Exception as e:
        print(f"\n❌ Error: {e}")
        import traceback
        traceback.print_exc()
    finally:
        # Cleanup will be handled by atexit and signal handlers
        pass

if __name__ == "__main__":
    main()