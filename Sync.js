import axios from "axios";

const SYNC_KEY = "MandhomService-102050*4098f";
const SYNC_URL = "http://vendexdz.com/sync";

console.log("🟡 Starting enhanced sync service...");

const runSync = async () => {
  const startTime = new Date();
  console.log(`🕐 Sync attempt at: ${startTime.toISOString()}`);
  
  try {
    const response = await axios.get(SYNC_URL, {
      params: { key: SYNC_KEY },
      timeout: 120000 // 2 دقيقة
    });

    const endTime = new Date();
    const duration = (endTime - startTime) / 1000;
    
    console.log(`✅ Sync completed in ${duration}s`);
    console.log(`📦 Response:`, response.data);
    
  } catch (error) {
    const endTime = new Date();
    const duration = (endTime - startTime) / 1000;
    
    console.error(`❌ Sync failed after ${duration}s`);
    
    if (error.response) {
      console.error(`📊 Status: ${error.response.status}`);
      console.error(`📄 Data:`, error.response.data);
    } else if (error.request) {
      console.error('🌐 No response received');
    } else {
      console.error('⚙️ Setup error:', error.message);
    }
  }
};

// تشغيل أولي
runSync();

// تكرار كل دقيقتين
setInterval(runSync,  2* 60 * 1000);