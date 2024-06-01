// authHeader.js
export function authHeader() {
    const token = 'a2d43e0926f4d68d17831389608edcdad5b78096bee59279185bf9b47cf067225587d9842e4cd4a4'; 
    return { Authorization: `Bearer ${token}` };
  }
