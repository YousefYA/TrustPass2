import { scrypt } from "@noble/hashes/scrypt.js";

const encoder = new TextEncoder();

export function createVerifier(password, salt) {
    const pw = encoder.encode(password);

    const hash = scrypt(pw, salt, {
        N: 2 ** 15,
        r: 8,
        p: 1,
        dkLen: 32,
    });

    return btoa(String.fromCharCode(...hash));
}
