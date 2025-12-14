export async function decrypt(keyBytes, encrypted) {
    const key = await crypto.subtle.importKey(
        "raw",
        keyBytes,
        "AES-GCM",
        false,
        ["decrypt"]
    );

    const iv = new Uint8Array(encrypted.iv);
    const data = new Uint8Array(encrypted.data);

    const plaintext = await crypto.subtle.decrypt(
        { name: "AES-GCM", iv },
        key,
        data
    );

    return new Uint8Array(plaintext);
}
