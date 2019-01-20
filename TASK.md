### JSON API for contact lens option selection
Goal is to create a demo json api that could be consumed by a vue.js app to retrieve contact lens option values. 

#### Background
Depending on the configuration of contact lenses certain combination of parameters are not allowed.

#### Example general
Parameter 1 options: A,B,C

Parameter 2 options: X,Y,Z

Where A/Y and C/Z are not allowed. 

The selection process is always selects one parameter after another. This will allow us to filter out not allowed combinations.

---
Example combinations

First parameter: parameter 1 B

Options for second parameter 2: X,Y,Y

---
First parameter: parameter 1 A
Options for second parameter 2: X,Z

---
First parameter: parameter 2: Z
Options for second parameter 1: A,B

#### Example reality
Here is a real life example of this problem from our competition

https://www.discountlens.ch/de/1-day-acuvue-oasys-astigmatism-30.html


## Task

Create an API to retrieve available parameters while respecting the not allowed combinations.

1. Think about how to solve this problem and write down at least 2 possible solutions
2. Discuss advantages and disadvantages of your chosen solution over the not chosen solution from 1)
3. Create your own sample data and a test api that implements this behavior. Create an endpoint to query and demonstrate

