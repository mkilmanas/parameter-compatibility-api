# parameter-compatibility-api

### Solution proposal #1

If possible, I would enquire more to try and understand the business logic driving this limitations. This way the logic could be written as logical rules in the code and executed on demand, possibly caching the results as they should not change as long as the rules do not change.

As there is likely more than one such rule restricting the selection Chain-of-Responsibility (or Strategy depending on how the restrictions are combined together) pattern could be used to process the set of available options in order to fullfil all the restriction requirements.

This would allow to have code resemble business logic closely, thus enabling easier understanding and maintainability of the code over time.

### Solution proposal #2

As the business rules are not always available to clearly express why and when parameter combinations are unavailable, or there may be a need to have possibility to modify the combinations by an administrator, a blanket solution would be to maintain a list of all a) available or b) disallowed combinantions (basically whitelist or blacklist strategry, depending on whethere there a more allowed or more prohibited combinations).

This solution is applicable in more situations (it can always replace the solution #1 whereas the inverse is not always true) at the cost of having to maintain the list of restricted combinations at the lower level of abstraction.

### Choice

Whilst solution #1 would be preferred whenever possible, in this case solution #2 was chosen because:

- There is no mention of a business domain in which this will be used, so it does not seem like there is a domain expert to talk to;
- Examples follow this logic of explicitly listing prohibited combinations;
