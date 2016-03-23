package com.touzhijia.platform.security;

import java.nio.charset.Charset;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Arrays;
import java.util.Base64;
import java.util.Base64.Decoder;
import java.util.Base64.Encoder;
import java.util.Random;

import javax.crypto.Cipher;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Component;

import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import com.touzhijia.platform.entity.Message;
import com.touzhijia.platform.entity.ServiceData;

@Component
public class MessageCrypt {
	private static Charset CHARSET = Charset.forName("utf-8");
	private Encoder base64Encoder = Base64.getEncoder();
	private Decoder base64Decoder = Base64.getDecoder();

	@Value("${aesKey}")
	private String aesKey;
	@Value("${token}")
	private String token;
	@Value("${appId}")
	private String appId;
	
	public Message encryptMsg(ServiceData sd) throws CryptException {
		String nonce = getRandomStr();
		Message msg = new Message();
		msg.setTimestamp(System.currentTimeMillis() / 1000);
		msg.setNonce(nonce);
		
		Gson gson = new Gson();
		byte[] jsonBytes = gson.toJson(sd).getBytes(CHARSET);
		byte[] networkBytesOrder = getNetworkBytesOrder(jsonBytes.length);
		byte[] appIdBytes = appId.getBytes(CHARSET);
		ByteGroup bg = new ByteGroup();
		bg.addBytes(nonce.getBytes(CHARSET));
		bg.addBytes(networkBytesOrder);
		bg.addBytes(jsonBytes);
		bg.addBytes(appIdBytes);
		byte[] padBytes = PKCS7Encoder.encode(bg.size());
		bg.addBytes(padBytes);
		// 获得最终的字节流, 未加密
		byte[] original = bg.toBytes();
		
		try {
			byte[] reqKey = getRequestKey(msg.getTimestamp());
			Cipher cipher = Cipher.getInstance("AES/CBC/NoPadding");
			SecretKeySpec key_spec = new SecretKeySpec(reqKey, "AES");
			IvParameterSpec iv = new IvParameterSpec(Arrays.copyOfRange(reqKey, 0, 16));
			cipher.init(Cipher.ENCRYPT_MODE, key_spec, iv);
			byte[] encrypted = cipher.doFinal(original);
			String data = base64Encoder.encodeToString(encrypted);
			msg.setData(data);
			String signature = SHA1.getSHA1(token, msg);
			msg.setSignature(signature);
		} catch (Exception e) {
			throw new CryptException(CryptException.EncryptAESError);
		}
		
		return msg;
	}
	
	public ServiceData decryptMsg(Message msg) throws CryptException {
		
		String signature = SHA1.getSHA1(token, msg);
		if (!signature.equals(msg.getSignature())) {
			throw new CryptException(CryptException.ValidateSignatureError);
		}
		try {
			byte[] reqKey = getRequestKey(msg.getTimestamp());
			Cipher cipher = Cipher.getInstance("AES/CBC/NoPadding");
			SecretKeySpec key_spec = new SecretKeySpec(reqKey, "AES");
			IvParameterSpec iv = new IvParameterSpec(Arrays.copyOfRange(reqKey, 0, 16));
			cipher.init(Cipher.DECRYPT_MODE, key_spec, iv);
			byte[] encrypted = base64Decoder.decode((msg.getData()));
			byte[] original = cipher.doFinal(encrypted);
			
			// 去除补位字符
			byte[] bytes = PKCS7Encoder.decode(original);
			// 分离16位随机字符串,网络字节序和AppId
			byte[] networkOrder = Arrays.copyOfRange(bytes, 16, 20);
			int dataLength = recoverNetworkBytesOrder(networkOrder);
			String json = new String(Arrays.copyOfRange(bytes, 20, 20 + dataLength), CHARSET);
			String appId = new String(Arrays.copyOfRange(bytes, 20 + dataLength, bytes.length),
					CHARSET);
			
			if (!appId.equals(this.appId)) {
				throw new CryptException(CryptException.ValidateAppidError);
			}
			
			JsonParser parser = new JsonParser();
			JsonObject root = parser.parse(json).getAsJsonObject();
			String service = root.get("service").getAsString();
			return new ServiceData(service, root.get("body"));
		} catch(Exception e) {
			throw new CryptException(CryptException.DecryptAESError);
		}
	}
	
	private byte[] getRequestKey(long timestamp) throws CryptException {
		String a = String.format("%s%d", aesKey, timestamp);
			MessageDigest md5;
			try {
				md5 = MessageDigest.getInstance("MD5");
				return md5.digest(a.getBytes(CHARSET));
			} catch (NoSuchAlgorithmException e) {
				throw new CryptException(CryptException.EncryptAESError);
			}
		
	}
	
	// 生成4个字节的网络字节序
	byte[] getNetworkBytesOrder(int sourceNumber) {
		byte[] orderBytes = new byte[4];
		orderBytes[3] = (byte) (sourceNumber & 0xFF);
		orderBytes[2] = (byte) (sourceNumber >> 8 & 0xFF);
		orderBytes[1] = (byte) (sourceNumber >> 16 & 0xFF);
		orderBytes[0] = (byte) (sourceNumber >> 24 & 0xFF);
		return orderBytes;
	}
	
	// 还原4个字节的网络字节序
	int recoverNetworkBytesOrder(byte[] orderBytes) {
		int sourceNumber = 0;
		for (int i = 0; i < 4; i++) {
			sourceNumber <<= 8;
			sourceNumber |= orderBytes[i] & 0xff;
		}
		return sourceNumber;
	}

	// 随机生成16位字符串
	String getRandomStr() {
		String base = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		Random random = new Random();
		StringBuffer sb = new StringBuffer();
		for (int i = 0; i < 16; i++) {
			int number = random.nextInt(base.length());
			sb.append(base.charAt(number));
		}
		return sb.toString();
	}
}
