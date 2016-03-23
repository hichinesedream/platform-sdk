package com.touzhijia.platform.security;

import java.security.MessageDigest;
import java.util.Arrays;

import com.touzhijia.platform.entity.Message;

class SHA1 {
	
	public static String getSHA1(String token, Message msg) throws CryptException {
		try {
			String[] array = new String[] { token, String.valueOf(msg.getTimestamp()), msg.getNonce(), msg.getData() };
			StringBuffer sb = new StringBuffer();
			// 字符串排序
			Arrays.sort(array);
			for (int i = 0; i < 4; i++) {
				sb.append(array[i]);
			}
			String str = sb.toString();
			// SHA1签名生成
			MessageDigest md = MessageDigest.getInstance("SHA-1");
			md.update(str.getBytes());
			byte[] digest = md.digest();

			StringBuffer hexstr = new StringBuffer();
			String shaHex = "";
			for (int i = 0; i < digest.length; i++) {
				shaHex = Integer.toHexString(digest[i] & 0xFF);
				if (shaHex.length() < 2) {
					hexstr.append(0);
				}
				hexstr.append(shaHex);
			}
			return hexstr.toString();
		} catch (Exception e) {
			e.printStackTrace();
			throw new CryptException(CryptException.ComputeSignatureError);
		}
	}

}
