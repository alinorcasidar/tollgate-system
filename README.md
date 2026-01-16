Here's a polished README.md with proper markdown formatting, sections, and styling:

```markdown
# 🛣️ CDO-MALAYBALAY Tollgate Management System

**A comprehensive RFID & Cash Tollgate Management System** built with HTML, CSS, JavaScript, and Firebase that simulates a real-world tollgate payment system for the CDO-MALAYBALAY route. This system handles both RFID card payments and cash transactions with vehicle classification and real-time database management.

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Firebase](https://img.shields.io/badge/Firebase-FFCA28?style=for-the-badge&logo=firebase&logoColor=black)
![Raspberry Pi](https://img.shields.io/badge/Raspberry%20Pi-A22846?style=for-the-badge&logo=raspberry-pi&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

---

## 🚀 Quick Start

### Prerequisites
- **Firebase account** with Realtime Database
- **Raspberry Pi** with GPIO setup (optional for hardware)
- Modern web browser

### Installation
```bash
# Clone the repository
git clone https://github.com/yourusername/tollgate-system.git

# Navigate to project directory
cd tollgate-system

# Open in browser
open index.html
```

**Live Demo:** [Add your deployment link here]

---

## ✨ Key Features

### 🔐 **Authentication & Security**
- **Firebase Email/Password Authentication** with secure user registration
- **Role-based access control** (Admin/User levels)
- **Password reset functionality** with email verification
- **Auto-login session persistence**

### 🚗 **Vehicle Classification System**
| Class | Vehicle Types | Toll Fee |
|-------|---------------|----------|
| **Class 1** | Regular Cars, Jeepneys, Vans | **₱119** |
| **Class 2** | Buses, Trucks | **₱299** |
| **Class 3** | Large Trucks, Trailers | **₱418** |

### 💳 **Dual Payment Methods**

#### **RFID Card System**
- **Tap-to-pay RFID card integration**
- Real-time balance deduction
- **20-second cooldown** between taps
- Automated gate control

#### **Cash Payment System**
- **Instant receipt generation**
- **Change calculation**
- Transaction logging
- Manual gate control

### 📱 **Dashboard Components**
- **Tollgate System Panel** – Real-time gate status indicators, RFID tap simulation
- **Card Registration Module** – Tap-to-register new RFID cards, vehicle class assignment
- **Admin Control Panel** – Total cards & balance statistics, card management
- **Transaction History** – Filterable logs, RFID vs Cash analytics, export capabilities

---

## 🛠️ Technology Stack

### **Frontend**
- **HTML5** – Semantic markup structure
- **CSS3** – Modern responsive design with Flexbox/Grid
- **JavaScript (ES6+)** – Dynamic functionality
- **Font Awesome** – Icon library

### **Backend & Database**
- **Firebase Authentication** – User management
- **Firebase Realtime Database** – Real-time data sync
- **Firebase Hosting** – Deployment

### **External APIs**
- **Raspberry Pi GPIO** – Hardware gate control
- **RFID Reader API** – Card scanning integration

---

## 📊 Database Structure

### **Collections**

#### **`users/`**
```
users/{userId}/
├── email
├── fullName
├── role (user/admin)
├── createdAt
└── lastLogin
```

#### **`rfidCards/`**
```
rfidCards/{cardId}/
├── holder (user name)
├── email
├── balance
├── status (active/inactive/blocked)
├── vehicleClass (class1/class2/class3)
├── vehicleDetails
└── dateCreated
```

#### **`transactions/`**
```
transactions/{transactionId}/
├── type (rfid/cash/balance_added)
├── cardId
├── amount
├── vehicleClass
├── timestamp
└── status
```

#### **`notifications/`**
```
notifications/{notificationId}/
├── type
├── message
├── timestamp
└── processed
```

---

## 🔧 Setup & Configuration

### **1. Firebase Setup**
```javascript
const firebaseConfig = {
  apiKey: "YOUR_API_KEY",
  authDomain: "YOUR_PROJECT.firebaseapp.com",
  databaseURL: "https://YOUR_PROJECT.firebaseio.com",
  projectId: "YOUR_PROJECT",
  storageBucket: "YOUR_PROJECT.appspot.com",
  messagingSenderId: "YOUR_SENDER_ID",
  appId: "YOUR_APP_ID"
};
```

### **2. Enable Authentication**
1. Go to **Firebase Console → Authentication → Sign-in method**
2. Enable **Email/Password provider**

### **3. Database Security Rules**
```json
{
  "rules": {
    "users": {
      "$userId": {
        ".read": "$userId === auth.uid",
        ".write": "$userId === auth.uid"
      }
    },
    "rfidCards": {
      ".read": "auth != null",
      ".write": "auth != null"
    }
  }
}
```

---

## 🚦 System Workflow

1. **📝 User Registration** → Create account with email/password
2. **🔐 Login** → Access dashboard based on role
3. **🆕 RFID Card Registration** → Tap card, assign vehicle class, load balance
4. **💳 Payment Processing** → Tap/scan or cash payment
5. **🚪 Gate Control** → Automated gate opening on successful payment
6. **📋 Transaction Recording** → All activities logged in real-time
7. **👨‍💼 Admin Management** → Monitor, manage, and control system

---

## 🎯 Use Cases

### **For Toll Operators:**
- 📊 **Monitor real-time transactions**
- 🏷️ **Manage RFID card inventory**
- 📈 **Generate financial reports**
- 🎮 **Control gate operations remotely**

### **For Vehicle Owners:**
- ⚡ **Fast RFID-based payments**
- 💰 **Balance tracking**
- 📜 **Transaction history**
- 🆔 **Card registration/management**

### **For System Administrators:**
- 👥 **User account management**
- ⚙️ **System configuration**
- 🗄️ **Database maintenance**
- 🔒 **Security monitoring**

---

## ⭐ Features & Benefits

| Feature | Benefit |
|---------|---------|
| **✅ Dual Payment System** | RFID + Cash flexibility |
| **✅ Real-time Dashboard** | Live transaction monitoring |
| **✅ Vehicle Classification** | Fair pricing based on vehicle type |
| **✅ Hardware Integration** | Raspberry Pi + RFID reader support |
| **✅ Cloud-based Management** | Access from anywhere |
| **✅ Scalable Architecture** | From single lane to multi-lane |

### **Performance Features**
- **🚀 Real-time Updates** – Instant database sync
- **📱 Responsive Design** – Works on desktop & mobile
- **📴 Offline Capability** – Local data persistence
- **⚡ Fast Processing** – Optimized transaction handling
- **📈 Scalable Architecture** – Supports multiple toll lanes

### **Security Measures**
- 🔒 **End-to-end encryption** for sensitive data
- ✅ **Input validation** on all forms
- ⏱️ **Session management** with auto-logout
- 👥 **Role-based permissions**
- 🌐 **Secure API endpoints**
- 🛡️ **XSS protection**

---

## 📚 Learning Outcomes

This project demonstrates:

- 🔨 **Full-stack web development** with Firebase
- 🔄 **Real-time database synchronization**
- 🔌 **Hardware-software integration**
- 💳 **Payment gateway simulation**
- 🔐 **User authentication & authorization**
- 🎨 **Responsive UI/UX design**
- 📋 **Project management** from concept to deployment

---


## 📞 Contact

**📧 Email:** casidar.alinor1017@gmail.com  
**💻 GitHub:** (https://github.com/alinorcasidar)
