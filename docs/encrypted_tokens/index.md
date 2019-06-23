# Encrypted tokens

To use the encrypted tokens (JWE), you have to install the 
[`web-token/jwt-encryption` component](https://github.com/web-token/jwt-encryption).

```
composer require web-token/jwt-encryption
```

When this component is installed, encryption algorithms are 
automatically handles by the Algorithm Manager Factory.

- [JWE serializers](./jwe_serializers.md)
- [JWE creation](./jwe_creation.md)
- [JWE decryption](./jwe_decryption.md)
