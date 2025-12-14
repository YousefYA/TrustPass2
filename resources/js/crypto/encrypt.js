export async function encrypt(keyBytes, dataBytes) {
    const iv = crypto.getRandomValues(new Uint8Array(12));

    const key = await crypto.subtle.importKey(
        "raw",
        keyBytes,
        { name: "AES-GCM" },
        false,
        ["encrypt"]
    );

    const ciphertext = await crypto.subtle.encrypt(
        { name: "AES-GCM", iv },
        key,
        dataBytes
    );

    return {
        iv: Array.from(iv),
        data: Array.from(new Uint8Array(ciphertext)),
    };
}
