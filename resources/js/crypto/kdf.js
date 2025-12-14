import { scrypt } from "@noble/hashes/scrypt.js";

const encoder = new TextEncoder();

export function deriveKey(password, salt) {
    const pw = encoder.encode(password);

    return scrypt(pw, salt, {
        N: 2 ** 15,
        r: 8,
        p: 1,
        dkLen: 32, // 256-bit key
    });
}
